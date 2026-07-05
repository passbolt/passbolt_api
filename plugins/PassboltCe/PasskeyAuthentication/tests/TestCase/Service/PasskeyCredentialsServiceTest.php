<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         5.14.0
 */
namespace Passbolt\PasskeyAuthentication\Test\TestCase\Service;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService;
use Passbolt\PasskeyAuthentication\Service\PasskeyCredentialsService;
use Passbolt\PasskeyAuthentication\Test\Factory\PasskeyCredentialFactory;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Webauthn\CredentialRecord;
use Webauthn\TrustPath\EmptyTrustPath;

/**
 * @group passkey
 */
class PasskeyCredentialsServiceTest extends AppTestCase
{
    /**
     * @var \Passbolt\PasskeyAuthentication\Service\PasskeyCredentialsService
     */
    protected PasskeyCredentialsService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new PasskeyCredentialsService();
    }

    /**
     * A persisted user id (the passkey_credentials.user_id column has a foreign key on users).
     *
     * @return string
     */
    protected function persistedUserId(): string
    {
        return UserFactory::make()->user()->persist()->id;
    }

    public function testListForUser_ReturnsOnlyThatUsersCredentialsOrderedByCreated(): void
    {
        $userId = $this->persistedUserId();
        PasskeyCredentialFactory::make()
            ->withUser($userId)
            ->patchData(['created' => DateTime::now()->subDays(3), 'name' => 'older'])
            ->persist();
        PasskeyCredentialFactory::make()
            ->withUser($userId)
            ->patchData(['created' => DateTime::now()->subDays(1), 'name' => 'newer'])
            ->persist();
        // Another user's credential must not appear.
        PasskeyCredentialFactory::make()->withUser($this->persistedUserId())->persist();

        $result = array_values(iterator_to_array($this->service->listForUser($userId)));

        $this->assertCount(2, $result);
        $this->assertSame('older', $result[0]->get('name'));
        $this->assertSame('newer', $result[1]->get('name'));
    }

    public function testCountForUser(): void
    {
        $userId = $this->persistedUserId();
        PasskeyCredentialFactory::make(3)->withUser($userId)->persist();
        PasskeyCredentialFactory::make()->withUser($this->persistedUserId())->persist();

        $this->assertSame(3, $this->service->countForUser($userId));
        $this->assertSame(0, $this->service->countForUser(UuidFactory::uuid()));
    }

    public function testHasCredentials(): void
    {
        $userId = $this->persistedUserId();
        $this->assertFalse($this->service->hasCredentials($userId));

        PasskeyCredentialFactory::make()->withUser($userId)->persist();

        $this->assertTrue($this->service->hasCredentials($userId));
    }

    public function testFindEntityByCredentialId(): void
    {
        $userId = $this->persistedUserId();
        PasskeyCredentialFactory::make()
            ->withUser($userId)
            ->patchData(['credential_id' => 'find-me'])
            ->persist();

        $found = $this->service->findEntityByCredentialId($userId, 'find-me');
        $this->assertNotNull($found);
        $this->assertSame('find-me', $found->get('credential_id'));

        $this->assertNull($this->service->findEntityByCredentialId($userId, 'nope'));
        // Scoped to the user: the credential id alone does not grant a match.
        $this->assertNull($this->service->findEntityByCredentialId(UuidFactory::uuid(), 'find-me'));
    }

    public function testDeleteOne_Found(): void
    {
        $userId = $this->persistedUserId();
        PasskeyCredentialFactory::make()
            ->withUser($userId)
            ->patchData(['credential_id' => 'known-id'])
            ->persist();

        $this->assertTrue($this->service->deleteOne($userId, 'known-id'));
        $this->assertSame(0, $this->service->countForUser($userId));
    }

    public function testDeleteOne_UnknownReturnsFalse(): void
    {
        $userId = $this->persistedUserId();
        PasskeyCredentialFactory::make()->withUser($userId)->persist();

        $this->assertFalse($this->service->deleteOne($userId, 'does-not-exist'));
        $this->assertSame(1, $this->service->countForUser($userId));
    }

    public function testDeleteOne_DoesNotDeleteAnotherUsersCredential(): void
    {
        $otherId = $this->persistedUserId();
        PasskeyCredentialFactory::make()
            ->withUser($otherId)
            ->patchData(['credential_id' => 'shared-id'])
            ->persist();

        // A different user cannot delete a credential they do not own.
        $this->assertFalse($this->service->deleteOne(UuidFactory::uuid(), 'shared-id'));
        $this->assertSame(1, $this->service->countForUser($otherId));
    }

    public function testDeleteAllForUser(): void
    {
        $userId = $this->persistedUserId();
        $otherId = $this->persistedUserId();
        PasskeyCredentialFactory::make(2)->withUser($userId)->persist();
        PasskeyCredentialFactory::make()->withUser($otherId)->persist();

        $this->service->deleteAllForUser($userId);

        $this->assertSame(0, $this->service->countForUser($userId));
        $this->assertSame(1, $this->service->countForUser($otherId));
    }

    public function testEncodeCredentialId_ProducesUrlSafeBase64WithoutPadding(): void
    {
        // Bytes chosen to produce "+" and "/" (and padding) under standard base64.
        $raw = "\x00\xff\xfe\xfa\xfb\xfc\x3e\x3f";
        $encoded = $this->service->encodeCredentialId($raw);

        $this->assertStringNotContainsString('+', $encoded);
        $this->assertStringNotContainsString('/', $encoded);
        $this->assertStringNotContainsString('=', $encoded);
        // Round-trips back to the original bytes.
        $this->assertSame($raw, base64_decode(strtr($encoded, '-_', '+/')));
    }

    public function testCreate_StoresCredentialWithNameAndEncodedId(): void
    {
        $userId = $this->persistedUserId();

        // Isolate the test from the webauthn-lib serializer: create() only needs a JSON string back.
        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->method('serialize')->willReturn('{"stored":"record"}');
        $relyingParty = $this->createMock(MfaWebauthnRelyingPartyService::class);
        $relyingParty->method('getSerializer')->willReturn($serializer);
        $service = new PasskeyCredentialsService($relyingParty);

        $record = new CredentialRecord(
            publicKeyCredentialId: 'raw-credential-id',
            type: 'public-key',
            transports: ['usb'],
            attestationType: 'none',
            trustPath: EmptyTrustPath::create(),
            aaguid: Uuid::v4(),
            credentialPublicKey: 'public-key-bytes',
            userHandle: 'user-handle',
            counter: 7,
        );

        $entity = $service->create($userId, $record, 'server-half-jwk', 'My key');

        $this->assertSame('My key', $entity->get('name'));
        $this->assertSame($userId, $entity->get('user_id'));
        $this->assertSame($service->encodeCredentialId('raw-credential-id'), $entity->get('credential_id'));
        $this->assertSame(7, $entity->get('sign_count'));
        $this->assertSame('server-half-jwk', $entity->get('server_kit_key'));
        $this->assertSame('{"stored":"record"}', $entity->get('data'));
        // It was persisted and can be found back through the user-scoped lookup.
        $this->assertSame(1, $service->countForUser($userId));
        $this->assertNotNull(
            $service->findEntityByCredentialId($userId, $service->encodeCredentialId('raw-credential-id'))
        );
    }
}
