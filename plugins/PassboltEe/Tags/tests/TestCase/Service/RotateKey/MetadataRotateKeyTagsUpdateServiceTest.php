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
 * @since         5.1.0
 */

namespace Passbolt\Tags\Test\TestCase\Service\RotateKey;

use App\Error\Exception\CustomValidationException;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Exception;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Tags\Model\Dto\MetadataTagDto;
use Passbolt\Tags\Service\RotateKey\MetadataRotateKeyTagsUpdateService;
use Passbolt\Tags\Test\Factory\TagFactory;

/**
 * @covers \Passbolt\Tags\Service\RotateKey\MetadataRotateKeyTagsUpdateService
 */
class MetadataRotateKeyTagsUpdateServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    /**
     * @var MetadataRotateKeyTagsUpdateService|null
     */
    private ?MetadataRotateKeyTagsUpdateService $service = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new MetadataRotateKeyTagsUpdateService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMetadataRotateKeyTagsUpdateService_Success(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // create metadata keys
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        $metadata = json_encode(MetadataTagDto::fromArray(['slug' => 'marketing'])->getClearTextMetadata());
        $expiredTag1 = TagFactory::make()
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();
        // another user's resource returned
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($user->get('gpgkey'))->persist();
        $metadata = json_encode(MetadataTagDto::fromArray(['slug' => 'customer support'])->getClearTextMetadata());
        $expiredTag2 = TagFactory::make()
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();

        $uac = $this->mockAdminAccessControl();
        $metadataToUpdateForT1 = $this->encryptForMetadataKey(json_encode(MetadataTagDto::fromArray(['slug' => 'f1 marketing updated'])->getClearTextMetadata()));
        $metadataToUpdateForT2 = $this->encryptForMetadataKey(json_encode(MetadataTagDto::fromArray(['slug' => 'f1 customer support updated'])->getClearTextMetadata()));
        $data = [
            [
                'id' => $expiredTag1->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataToUpdateForT1,
            ],
            [
                'id' => $expiredTag2->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataToUpdateForT2,
            ],
        ];
        $this->service->updateMany($uac, $data);

        /** @var \Passbolt\Tags\Model\Entity\Tag $updatedTag1 */
        $updatedTag1 = TagFactory::get($expiredTag1->get('id'));
        $this->assertSame($activeMetadataKey->get('id'), $updatedTag1->get('metadata_key_id'));
        $this->assertSame($metadataToUpdateForT1, $updatedTag1->get('metadata'));
        /** @var \Passbolt\Tags\Model\Entity\Tag $updatedTag2 */
        $updatedTag2 = TagFactory::get($expiredTag2->get('id'));
        $this->assertSame($activeMetadataKey->get('id'), $updatedTag2->get('metadata_key_id'));
        $this->assertSame($metadataToUpdateForT2, $updatedTag2->get('metadata'));
    }

    public function testMetadataRotateKeyTagsUpdateService_Error_EmptyData(): void
    {
        $uac = $this->mockAdminAccessControl();
        $this->expectException(BadRequestException::class);
        $this->service->updateMany($uac, []);
    }

    public function testMetadataRotateKeyTagsUpdateService_Error_DataIsNotAnArray(): void
    {
        $uac = $this->mockAdminAccessControl();
        $this->expectException(BadRequestException::class);
        $this->service->updateMany($uac, ['foo', 'bar', 'baz']);
    }

    public function metadataRotateKeyResourcesUpdateServiceInvalidMetadataEncryptedMessage(): array
    {
        return [
            ['🔥'],
            ['foo-bar'],
        ];
    }

    /**
     * @dataProvider metadataRotateKeyResourcesUpdateServiceInvalidMetadataEncryptedMessage
     * @param mixed $metadata Invalid metadata.
     * @return void
     */
    public function testMetadataRotateKeyTagsUpdateService_Error_InvalidMetadataEncryptedMessage($metadata): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        $tag = TagFactory::make()
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();
        $uac = $this->makeUac($admin);
        $data = [
            [
                'id' => $tag->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadata,
            ],
        ];

        try {
            $this->service->updateMany($uac, $data);
        } catch (CustomValidationException $e) {
            $errors = $e->getErrors();
            $this->assertCount(1, $errors);
            $this->assertArrayHasKey('isMetadataParsable', $errors[0]['metadata']);
        }
    }

    public function testMetadataRotateKeyTagsUpdateService_Error_TagNotFound(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $uac = $this->makeUac($admin);
        $metadata = json_encode(MetadataTagDto::fromArray(['slug' => 'marketing'])->getClearTextMetadata());
        $data = [
            [
                'id' => UuidFactory::uuid(),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForMetadataKey($metadata),
            ],
        ];

        $this->expectException(NotFoundException::class);
        $this->service->updateMany($uac, $data);
    }

    public function testMetadataRotateKeyTagsUpdateService_Error_MetadataKeyIsExpired(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        $metadata = json_encode(MetadataTagDto::fromArray(['slug' => 'marketing'])->getClearTextMetadata());
        $tag = TagFactory::make()
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();

        try {
            $uac = $this->mockAdminAccessControl();
            $metadataToUpdate = json_encode(MetadataTagDto::fromArray(['slug' => 'marketing - updated'])->getClearTextMetadata());
            $data = [
                [
                    'id' => $tag->get('id'),
                    'metadata_key_id' => $expiredMetadataKey->get('id'),
                    'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                    'metadata' => $this->encryptForMetadataKey($metadataToUpdate),
                ],
            ];
            $this->service->updateMany($uac, $data);
        } catch (Exception $e) {
            $this->assertInstanceOf(CustomValidationException::class, $e);
            $errors = $e->getErrors();
            $this->assertArrayHasKey('isMetadataKeyNotExpired', $errors[0]['metadata_key_id']);
        }
    }

    public function testMetadataRotateKeyTagsUpdateService_Error_Multiple_Active_Metadata_Keys(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // create metadata keys
        [$activeMetadataKey] = MetadataKeyFactory::make(2)->withServerPrivateKey()->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        $metadata = json_encode(MetadataTagDto::fromArray(['slug' => 'marketing'])->getClearTextMetadata());
        $tag = TagFactory::make()
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();

        $uac = $this->mockAdminAccessControl();
        $metadataToUpdateForT1 = $this->encryptForMetadataKey(json_encode(MetadataTagDto::fromArray(['slug' => 'f1 marketing updated'])->getClearTextMetadata()));
        $data = [
            [
                'id' => $tag->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataToUpdateForT1,
            ],
        ];
        try {
            $this->service->updateMany($uac, $data);
        } catch (Exception $e) {
            $this->assertInstanceOf(CustomValidationException::class, $e);
            $errors = $e->getErrors();
            $this->assertArrayHasKey('isSharedMetadataKeyUniqueActive', $errors[0]['metadata_key_id']);
        }
    }
}
