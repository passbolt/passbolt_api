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

namespace Passbolt\Metadata\Test\TestCase\Service\Upgrade;

use App\Error\Exception\CustomValidationException;
use App\Test\Factory\GroupFactory;
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
use Passbolt\Metadata\Service\Upgrade\MetadataUpgradeResourcesUpdateService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * @covers \Passbolt\Metadata\Service\Upgrade\MetadataUpgradeResourcesUpdateService
 */
class MetadataUpgradeResourcesUpdateServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    private ?MetadataUpgradeResourcesUpdateService $service = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new MetadataUpgradeResourcesUpdateService();

        ResourceTypeFactory::make()->default()->persist();
        ResourceTypeFactory::make()->passwordAndDescription()->persist();
        ResourceTypeFactory::make()->v5Default()->persist();
        ResourceTypeFactory::make()->v5PasswordString()->persist();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMetadataUpgradeResourcesUpdateService_Success(): void
    {
        // create metadata keys
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->persist();
        /** @var \App\Model\Entity\User $userWithPersonalResource */
        $userWithPersonalResource = UserFactory::make()->withAdaKey()->persist();
        $userKeyId = $userWithPersonalResource->gpgkey->id;
        $group = GroupFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resourceShared */
        $resourceShared = ResourceFactory::make()->withPermissionsFor([$group])->persist();
        /** @var \App\Model\Entity\Resource $resourcePersonal */
        $resourcePersonal = ResourceFactory::make()->withPermissionsFor([$userWithPersonalResource])->persist();

        $uac = $this->mockAdminAccessControl();
        $metadataForR1 = $this->encryptForUser(json_encode([]), $userWithPersonalResource, $this->getAdaNoPassphraseKeyInfo());
        $metadataForR2 = $this->encryptForMetadataKey(json_encode([]));
        $data = [
            [
                'id' => $resourcePersonal->get('id'),
                'metadata_key_id' => $userKeyId,
                'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
                'metadata' => $metadataForR1,
                'modified' => $resourcePersonal->get('modified')->format('Y-m-d H:i:s'),
                'modified_by' => $resourcePersonal->get('modified_by'),
            ],
            [
                'id' => $resourceShared->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataForR2,
                'modified' => $resourceShared->get('modified')->format('Y-m-d H:i:s'),
                'modified_by' => $resourceShared->get('modified_by'),
            ],
        ];
        $this->service->updateMany($uac, $data);

        $expectedResourceType = ResourceTypeFactory::find()->where(['slug' => ResourceType::SLUG_V5_DEFAULT])->firstOrFail();
        $updatedPersonalResource = ResourceFactory::get($resourcePersonal->get('id'));
        $this->assertSame($userKeyId, $updatedPersonalResource->get('metadata_key_id'));
        $this->assertSame($metadataForR1, $updatedPersonalResource->get('metadata'));
        $this->assertSame(Chronos::now()->format('Y-m-d H:i'), $updatedPersonalResource->get('modified')->format('Y-m-d H:i')); // comparing seconds here might fail
        $this->assertSame($uac->getId(), $updatedPersonalResource->get('modified_by'));
        $this->assertSame($expectedResourceType->get('id'), $updatedPersonalResource->get('resource_type_id'));
        $updatedSharedResource = ResourceFactory::get($resourceShared->get('id'));
        $this->assertSame($activeMetadataKey->get('id'), $updatedSharedResource->get('metadata_key_id'));
        $this->assertSame($metadataForR2, $updatedSharedResource->get('metadata'));
        $this->assertSame(Chronos::now()->format('Y-m-d H:i'), $updatedSharedResource->get('modified')->format('Y-m-d H:i'));
        $this->assertSame($uac->getId(), $updatedSharedResource->get('modified_by'));
        $this->assertSame($expectedResourceType->get('id'), $updatedSharedResource->get('resource_type_id'));
    }

    public function testMetadataUpgradeResourcesUpdateService_Error_EmptyData(): void
    {
        $uac = $this->mockAdminAccessControl();
        $this->expectException(BadRequestException::class);
        $this->service->updateMany($uac, []);
    }

    public function testMetadataUpgradeResourcesUpdateService_Error_DataIsNotAnArray(): void
    {
        $uac = $this->mockAdminAccessControl();
        $this->expectException(BadRequestException::class);
        $this->service->updateMany($uac, ['foo', 'bar', 'baz']);
    }

    public function MetadataUpgradeResourcesUpdateServiceInvalidMetadataEncryptedMessage(): array
    {
        return [
            ['🔥'],
            ['foo-bar'],
        ];
    }

    /**
     * @dataProvider MetadataUpgradeResourcesUpdateServiceInvalidMetadataEncryptedMessage
     * @param mixed $metadata Invalid metadata.
     * @return void
     */
    public function testMetadataUpgradeResourcesUpdateService_Error_InvalidMetadataEncryptedMessage($metadata): void
    {
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $resource = ResourceFactory::make()->persist();
        $uac = $this->mockAdminAccessControl();
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

    public function testMetadataUpgradeResourcesUpdateService_Error_ResourceNotFound(): void
    {
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $uac = $this->mockAdminAccessControl();
        $data = [
            [
                'id' => UuidFactory::uuid(),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => 'foo',
                'modified' => DateTime::now(),
                'modified_by' => $uac->getId(),
            ],
        ];

        $this->expectException(NotFoundException::class);
        $this->service->updateMany($uac, $data);
    }

    public function testMetadataUpgradeResourcesUpdateService_Error_ResourceDeleted(): void
    {
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $resource = ResourceFactory::make()->deleted()->persist();
        $uac = $this->mockAdminAccessControl();
        $data = [
            [
                'id' => $resource->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => 'foo',
                'modified' => $resource->get('modified'),
                'modified_by' => $resource->get('modified_by'),
            ],
        ];

        $this->expectException(NotFoundException::class);
        $this->service->updateMany($uac, $data);
    }

    public function testMetadataUpgradeResourcesUpdateService_Error_ModifiedDateConflict(): void
    {
        $resource = ResourceFactory::make(['modified' => DateTime::yesterday()])->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $uac = $this->mockAdminAccessControl();

        $data = [
            [
                'id' => $resource->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForMetadataKey(json_encode([])),
                'modified' => DateTime::now(),
                'modified_by' => $resource->get('modified_by'),
            ],
        ];

        $this->expectException(ConflictException::class);
        $this->service->updateMany($uac, $data);
    }

    public function testMetadataUpgradeResourcesUpdateService_Error_ModifiedByConflict(): void
    {
        $resource = ResourceFactory::make()->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $uac = $this->mockAdminAccessControl();
        $data = [
            [
                'id' => $resource->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForMetadataKey(json_encode([])),
                'modified' => $resource->get('modified'),
                'modified_by' => UuidFactory::uuid(),
            ],
        ];

        $this->expectException(ConflictException::class);
        $this->service->updateMany($uac, $data);
    }

    public function testMetadataUpgradeResourcesUpdateService_Error_MetadataKeyIsExpired(): void
    {
        $uac = $this->mockAdminAccessControl();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->persist();
        $resource = ResourceFactory::make()->persist();

        try {
            $data = [
                [
                    'id' => $resource->get('id'),
                    'metadata_key_id' => $expiredMetadataKey->get('id'),
                    'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                    'metadata' => $this->encryptForMetadataKey(json_encode([])),
                    'modified' => $resource->get('modified'),
                    'modified_by' => $resource->get('modified_by'),
                ],
            ];
            $this->service->updateMany($uac, $data);
        } catch (Exception $e) {
            $this->assertInstanceOf(CustomValidationException::class, $e);
            $errors = $e->getErrors();
            $this->assertArrayHasKey('isMetadataKeyNotExpired', $errors[0]['metadata_key_id']);
        }
    }

    public function testMetadataUpgradeResourcesUpdateService_Error_MetadataKeyIsDeleted(): void
    {
        $uac = $this->mockAdminAccessControl();
        // create metadata keys
        $deletedMetadataKey = MetadataKeyFactory::make()->deleted()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($deletedMetadataKey)->persist();
        $resource = ResourceFactory::make()->persist();

        try {
            $data = [
                [
                    'id' => $resource->get('id'),
                    'metadata_key_id' => $deletedMetadataKey->get('id'),
                    'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                    'metadata' => $this->encryptForMetadataKey(json_encode([])),
                    'modified' => $resource->get('modified'),
                    'modified_by' => $resource->get('modified_by'),
                ],
            ];
            $this->service->updateMany($uac, $data);
        } catch (Exception $e) {
            $this->assertInstanceOf(CustomValidationException::class, $e);
            $errors = $e->getErrors();
            $this->assertSame(
                ['metadata_key_exists' => 'The metadata key does not exist or was deleted.'],
                $errors[0]['metadata_key_id']
            );
        }
    }

    public function testMetadataUpgradeResourcesUpdateService_Resource_Already_v5(): void
    {
        // create metadata keys
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->persist();
        $resource = ResourceFactory::make()->v5Fields()->persist();

        $uac = $this->mockAdminAccessControl();
        $metadataForR1 = $this->encryptForMetadataKey(json_encode([]));
        $data = [
            [
                'id' => $resource->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataForR1,
                'modified' => $resource->get('modified'),
                'modified_by' => $resource->get('modified_by'),
            ],

        ];
        try {
            $this->service->updateMany($uac, $data);
        } catch (NotFoundException $exception) {
            $this->assertSame("Entity {$resource->get('id')} not found.", $exception->getMessage());
        }
    }

    public function testMetadataUpgradeResourcesUpdateService_Non_Metadata_Fields_Ignored(): void
    {
        // create metadata keys
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->persist();
        $resource = ResourceFactory::make()->persist();

        $uac = $this->mockAdminAccessControl();
        $metadataForR1 = $this->encryptForMetadataKey(json_encode([]));
        $data = [
            [
                'id' => $resource->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataForR1,
                'modified' => $resource->get('modified'),
                'modified_by' => $resource->get('modified_by'),
                'expired' => DateTime::yesterday(),
            ],
        ];
        $this->service->updateMany($uac, $data);
        $resourceUpdated = ResourceFactory::firstOrFail();
        $this->assertSame($resource->get('expired'), $resourceUpdated->expired);
    }

    public function testMetadataUpgradeResourcesUpdateService_v4_Fields_Set_To_Null(): void
    {
        // create metadata keys
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->persist();
        $resource = ResourceFactory::make([
            'name' => 'foo',
            'username' => 'bar',
            'uri' => 'foo',
            'description' => 'bar',
        ])->persist();

        $uac = $this->mockAdminAccessControl();
        $metadataForR1 = $this->encryptForMetadataKey(json_encode([]));
        $data = [
            [
                'id' => $resource->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataForR1,
                'modified' => $resource->get('modified'),
                'modified_by' => $resource->get('modified_by'),
                'expired' => DateTime::yesterday(),
            ],
        ];
        $this->service->updateMany($uac, $data);
        $resourceUpdated = ResourceFactory::firstOrFail();
        $this->assertSame($resource->get('expired'), $resourceUpdated->expired);
        $this->assertNull($resourceUpdated->name);
        $this->assertNull($resourceUpdated->username);
        $this->assertNull($resourceUpdated->uri);
        $this->assertNull($resourceUpdated->description);
    }
}
