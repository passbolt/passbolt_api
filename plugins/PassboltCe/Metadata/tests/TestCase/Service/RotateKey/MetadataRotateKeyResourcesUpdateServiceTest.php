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
 * @since         4.11.0
 */

namespace Passbolt\Metadata\Test\TestCase\Service\RotateKey;

use App\Error\Exception\CustomValidationException;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ConflictException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\DateTime;
use Exception;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Service\RotateKey\MetadataRotateKeyResourcesUpdateService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * @covers \Passbolt\Metadata\Service\RotateKey\MetadataRotateKeyResourcesUpdateService
 */
class MetadataRotateKeyResourcesUpdateServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    /**
     * @var MetadataRotateKeyResourcesUpdateService|null
     */
    private ?MetadataRotateKeyResourcesUpdateService $service = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new MetadataRotateKeyResourcesUpdateService();

        ResourceTypeFactory::make()->default()->persist();
        ResourceTypeFactory::make()->passwordAndDescription()->persist();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMetadataRotateKeyResourcesUpdateService_Success(): void
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
        $expiredResource1 = ResourceFactory::make()->withPermissionsFor([$admin])
            ->patchData([
                'metadata_key_id' => $expiredMetadataKey->get('id'),
                'metadata' => $this->encryptForMetadataKey(json_encode([])),
                'metadata_key_type' => 'shared_key',
                // Set V4 fields to null
                'name' => null,
                'username' => null,
                'uri' => null,
                'description' => null,
            ])->persist();
        // another user's resource returned
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($user->get('gpgkey'))->persist();
        $expiredResource2 = ResourceFactory::make()->withPermissionsFor([$user])->patchData([
            'metadata_key_id' => $expiredMetadataKey->get('id'),
            'metadata' => $this->encryptForMetadataKey(json_encode([])),
            'metadata_key_type' => 'shared_key',
            // Set V4 fields to null
            'name' => null,
            'username' => null,
            'uri' => null,
            'description' => null,
        ])->persist();

        $uac = $this->mockAdminAccessControl();
        $metadataForR1 = $this->encryptForMetadataKey(json_encode([]));
        $metadataForR2 = $this->encryptForMetadataKey(json_encode([]));
        $data = [
            [
                'id' => $expiredResource1->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataForR1,
                'modified' => $expiredResource1->get('modified')->format('Y-m-d H:i:s'),
                'modified_by' => $expiredResource1->get('modified_by'),
            ],
            [
                'id' => $expiredResource2->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataForR2,
                'modified' => $expiredResource2->get('modified')->format('Y-m-d H:i:s'),
                'modified_by' => $expiredResource2->get('modified_by'),
            ],
        ];
        $this->service->updateMany($uac, $data);

        /** @var \App\Model\Entity\Resource $updatedResource1 */
        $updatedResource1 = ResourceFactory::get($expiredResource1->get('id'));
        $this->assertSame($activeMetadataKey->get('id'), $updatedResource1->get('metadata_key_id'));
        $this->assertSame($metadataForR1, $updatedResource1->get('metadata'));
        $this->assertSame(Chronos::now()->format('Y-m-d'), $updatedResource1->get('modified')->format('Y-m-d')); // comparing seconds here might fail
        $this->assertSame($uac->getId(), $updatedResource1->get('modified_by'));
        /** @var \App\Model\Entity\Resource $updatedResource2 */
        $updatedResource2 = ResourceFactory::get($expiredResource2->get('id'));
        $this->assertSame($activeMetadataKey->get('id'), $updatedResource2->get('metadata_key_id'));
        $this->assertSame($metadataForR2, $updatedResource2->get('metadata'));
        $this->assertSame(Chronos::now()->format('Y-m-d'), $updatedResource2->get('modified')->format('Y-m-d'));
        $this->assertSame($uac->getId(), $updatedResource2->get('modified_by'));
    }

    public function testMetadataRotateKeyResourcesUpdateService_Error_EmptyData(): void
    {
        $uac = $this->mockAdminAccessControl();
        $this->expectException(BadRequestException::class);
        $this->service->updateMany($uac, []);
    }

    public function testMetadataRotateKeyResourcesUpdateService_Error_DataIsNotAnArray(): void
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
    public function testMetadataRotateKeyResourcesUpdateService_Error_InvalidMetadataEncryptedMessage($metadata): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$admin])->v5Fields(true, [
            'metadata_key_id' => $expiredMetadataKey->get('id'),
            'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
        ])->persist();
        $uac = $this->makeUac($admin);
        $data = [
            [
                'id' => $resource->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadata,
                'modified' => $resource->get('modified'),
                'modified_by' => $resource->get('modified_by'),
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

    public function testMetadataRotateKeyResourcesUpdateService_Error_ResourceNotFound(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $uac = $this->makeUac($admin);
        $data = [
            [
                'id' => UuidFactory::uuid(),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
                'modified' => DateTime::now(),
                'modified_by' => $admin->get('id'),
            ],
        ];

        $this->expectException(NotFoundException::class);
        $this->service->updateMany($uac, $data);
    }

    public function testMetadataRotateKeyResourcesUpdateService_Error_ResourceDeleted(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$admin])->deleted()->v5Fields(true, [
            'metadata_key_id' => UuidFactory::uuid(),
            'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
        ])->persist();
        $uac = $this->makeUac($admin);
        $data = [
            [
                'id' => $resource->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
                'modified' => $resource->get('modified'),
                'modified_by' => $resource->get('modified_by'),
            ],
        ];

        $this->expectException(NotFoundException::class);
        $this->service->updateMany($uac, $data);
    }

    public function testMetadataRotateKeyResourcesUpdateService_Error_ModifiedDateConflict(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        $resource = ResourceFactory::make(['modified' => DateTime::yesterday()])->withPermissionsFor([$admin])->v5Fields(true, [
            'metadata_key_id' => $expiredMetadataKey->get('id'),
            'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
        ])->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $uac = $this->makeUac($admin);
        $data = [
            [
                'id' => $resource->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
                'modified' => DateTime::now(),
                'modified_by' => $admin->get('id'),
            ],
        ];

        $this->expectException(ConflictException::class);
        $this->service->updateMany($uac, $data);
    }

    public function testMetadataRotateKeyResourcesUpdateService_Error_ModifiedByConflict(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        $resource = ResourceFactory::make(['modified' => DateTime::yesterday()])->withPermissionsFor([$admin])->v5Fields(true, [
            'metadata_key_id' => $expiredMetadataKey->get('id'),
            'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
        ])->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $uac = $this->makeUac($admin);
        $data = [
            [
                'id' => $resource->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
                'modified' => $resource->get('modified'),
                'modified_by' => UuidFactory::uuid(),
            ],
        ];

        $this->expectException(ConflictException::class);
        $this->service->updateMany($uac, $data);
    }

    public function testMetadataRotateKeyResourcesUpdateService_Error_MetadataKeyIsExpired(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        $expiredResource1 = ResourceFactory::make()->withPermissionsFor([$admin])->v5Fields(true, [
            'metadata_key_id' => $expiredMetadataKey->get('id'),
            'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
        ])->persist();

        try {
            $uac = $this->mockAdminAccessControl();
            $data = [
                [
                    'id' => $expiredResource1->get('id'),
                    'metadata_key_id' => $expiredMetadataKey->get('id'),
                    'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                    'metadata' => $this->encryptForMetadataKey(json_encode([])),
                    'modified' => $expiredResource1->get('modified'),
                    'modified_by' => $expiredResource1->get('modified_by'),
                ],
            ];
            $this->service->updateMany($uac, $data);
        } catch (Exception $e) {
            $this->assertInstanceOf(CustomValidationException::class, $e);
            $errors = $e->getErrors();
            $this->assertArrayHasKey('isMetadataKeyNotExpired', $errors[0]['metadata_key_id']);
        }
    }

    public function testMetadataRotateKeyResourcesUpdateService_Error_Multiple_Active_Metadata_Keys(): void
    {
        // create metadata keys
        [$activeMetadataKey] = MetadataKeyFactory::make(2)->withServerPrivateKey()->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->persist();
        $resource = ResourceFactory::make()->v5Fields(true, [
            'metadata_key_id' => $expiredMetadataKey->get('id'),
            'metadata' => $this->encryptForMetadataKey(json_encode([])),
        ])->persist();
        // another user's resource returned
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($user->get('gpgkey'))->persist();

        $metadata = $this->encryptForMetadataKey(json_encode([]));
        $data = [
            [
                'id' => $resource->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadata,
                'modified' => $resource->get('modified')->format('Y-m-d H:i:s'),
                'modified_by' => $resource->get('modified_by'),
            ],
        ];
        $uac = $this->mockAdminAccessControl();
        try {
            $this->service->updateMany($uac, $data);
        } catch (Exception $e) {
            $this->assertInstanceOf(CustomValidationException::class, $e);
            $errors = $e->getErrors();
            $this->assertArrayHasKey('isSharedMetadataKeyUniqueActive', $errors[0]['metadata_key_id']);
        }
    }
}
