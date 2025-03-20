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
 * @since         4.12.0
 */

namespace Passbolt\Metadata\Test\TestCase\Service\Upgrade;

use App\Error\Exception\CustomValidationException;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ConflictException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\DateTime;
use Exception;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Service\Upgrade\MetadataUpgradeFoldersUpdateService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Metadata\Service\Upgrade\MetadataUpgradeFoldersUpdateService
 */
class MetadataUpgradeFoldersUpdateServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    private ?MetadataUpgradeFoldersUpdateService $service = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new MetadataUpgradeFoldersUpdateService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMetadataUpgradeFoldersUpdateService_Success(): void
    {
        // create metadata keys
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->persist();
        [$folderPersonal, $folderShared] = FolderFactory::make(2)->persist();

        /** @var \App\Model\Entity\User $userWithPersonalFolder */
        $userWithPersonalFolder = UserFactory::make()->withAdaKey()->persist();
        $userKeyId = $userWithPersonalFolder->gpgkey->id;
        $group = GroupFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $folderShared */
        $folderShared = FolderFactory::make()->withPermissionsFor([$group])->persist();
        /** @var \Passbolt\Folders\Model\Entity\Folder $folderPersonal */
        $folderPersonal = FolderFactory::make()->withPermissionsFor([$userWithPersonalFolder])->persist();

        $uac = $this->mockAdminAccessControl();
        $metadataForF1 = $this->encryptForUser(json_encode([]), $userWithPersonalFolder, $this->getAdaNoPassphraseKeyInfo());
        $metadataForF2 = $this->encryptForMetadataKey(json_encode([]));
        $data = [
            [
                'id' => $folderPersonal->get('id'),
                'metadata_key_id' => $userKeyId,
                'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
                'metadata' => $metadataForF1,
                'modified' => $folderPersonal->get('modified')->format('Y-m-d H:i:s'),
                'modified_by' => $folderPersonal->get('modified_by'),
            ],
            [
                'id' => $folderShared->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataForF2,
                'modified' => $folderShared->get('modified')->format('Y-m-d H:i:s'),
                'modified_by' => $folderShared->get('modified_by'),
            ],
        ];
        $this->service->updateMany($uac, $data);

        $updatedFolder1 = FolderFactory::get($folderPersonal->get('id'));
        $this->assertSame($userKeyId, $updatedFolder1->get('metadata_key_id'));
        $this->assertSame($metadataForF1, $updatedFolder1->get('metadata'));
        $this->assertSame(Chronos::now()->format('Y-m-d H:i'), $updatedFolder1->get('modified')->format('Y-m-d H:i')); // comparing seconds here might fail
        $this->assertSame($uac->getId(), $updatedFolder1->get('modified_by'));
        $updatedFolder2 = FolderFactory::get($folderShared->get('id'));
        $this->assertSame($activeMetadataKey->get('id'), $updatedFolder2->get('metadata_key_id'));
        $this->assertSame($metadataForF2, $updatedFolder2->get('metadata'));
        $this->assertSame(Chronos::now()->format('Y-m-d H:i'), $updatedFolder2->get('modified')->format('Y-m-d H:i'));
        $this->assertSame($uac->getId(), $updatedFolder2->get('modified_by'));
    }

    public function testMetadataUpgradeFoldersUpdateService_Error_EmptyData(): void
    {
        $uac = $this->mockAdminAccessControl();
        $this->expectException(BadRequestException::class);
        $this->service->updateMany($uac, []);
    }

    public function testMetadataUpgradeFoldersUpdateService_Error_DataIsNotAnArray(): void
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
    public function testMetadataUpgradeFoldersUpdateService_Error_InvalidMetadataEncryptedMessage($metadata): void
    {
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $folder = FolderFactory::make()->persist();
        $uac = $this->mockAdminAccessControl();
        $data = [
            [
                'id' => $folder->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadata,
                'modified' => $folder->get('modified'),
                'modified_by' => $folder->get('modified_by'),
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

    public function testMetadataUpgradeFoldersUpdateService_Error_ResourceNotFound(): void
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

    public function testMetadataUpgradeFoldersUpdateService_Error_ModifiedDateConflict(): void
    {
        $folder = FolderFactory::make(['modified' => DateTime::yesterday()])->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $uac = $this->mockAdminAccessControl();

        $data = [
            [
                'id' => $folder->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForMetadataKey(json_encode([])),
                'modified' => DateTime::now(),
                'modified_by' => $folder->get('modified_by'),
            ],
        ];

        $this->expectException(ConflictException::class);
        $this->service->updateMany($uac, $data);
    }

    public function testMetadataUpgradeFoldersUpdateService_Error_ModifiedByConflict(): void
    {
        $folder = FolderFactory::make()->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $uac = $this->mockAdminAccessControl();
        $data = [
            [
                'id' => $folder->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForMetadataKey(json_encode([])),
                'modified' => $folder->get('modified'),
                'modified_by' => UuidFactory::uuid(),
            ],
        ];

        $this->expectException(ConflictException::class);
        $this->service->updateMany($uac, $data);
    }

    public function testMetadataUpgradeFoldersUpdateService_Error_MetadataKeyIsExpired(): void
    {
        $uac = $this->mockAdminAccessControl();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->persist();
        $folder = FolderFactory::make()->persist();

        try {
            $data = [
                [
                    'id' => $folder->get('id'),
                    'metadata_key_id' => $expiredMetadataKey->get('id'),
                    'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                    'metadata' => $this->encryptForMetadataKey(json_encode([])),
                    'modified' => $folder->get('modified'),
                    'modified_by' => $folder->get('modified_by'),
                ],
            ];
            $this->service->updateMany($uac, $data);
        } catch (Exception $e) {
            $this->assertInstanceOf(CustomValidationException::class, $e);
            $errors = $e->getErrors();
            $this->assertArrayHasKey('isMetadataKeyNotExpired', $errors[0]['metadata_key_id']);
        }
    }

    public function testMetadataUpgradeFoldersUpdateService_Error_MetadataKeyIsDeleted(): void
    {
        $uac = $this->mockAdminAccessControl();
        // create metadata keys
        $deletedMetadataKey = MetadataKeyFactory::make()->deleted()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($deletedMetadataKey)->persist();
        $folder = FolderFactory::make()->persist();

        try {
            $data = [
                [
                    'id' => $folder->get('id'),
                    'metadata_key_id' => $deletedMetadataKey->get('id'),
                    'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                    'metadata' => $this->encryptForMetadataKey(json_encode([])),
                    'modified' => $folder->get('modified'),
                    'modified_by' => $folder->get('modified_by'),
                ],
            ];
            $this->service->updateMany($uac, $data);
        } catch (Exception $e) {
            $this->assertInstanceOf(CustomValidationException::class, $e);
            $errors = $e->getErrors();
            $this->assertSame(
                ['metadata_key_exists' => 'The metadata key does not exist.'],
                $errors[0]['metadata_key_id']
            );
        }
    }

    public function testMetadataUpgradeFoldersUpdateService_Folder_Already_v5(): void
    {
        // create metadata keys
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->persist();
        $folder = FolderFactory::make()->v5Fields(['metadata' => 'FOO'])->persist();

        $uac = $this->mockAdminAccessControl();
        $metadataForF1 = $this->encryptForMetadataKey(json_encode([]));
        $data = [
            [
                'id' => $folder->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataForF1,
                'modified' => $folder->get('modified'),
                'modified_by' => $folder->get('modified_by'),
            ],

        ];
        try {
            $this->service->updateMany($uac, $data);
        } catch (NotFoundException $exception) {
            $this->assertSame("Entity {$folder->get('id')} not found.", $exception->getMessage());
        }
    }

    public function testMetadataUpgradeFoldersUpdateService_v4_Fields_Set_To_Null(): void
    {
        // create metadata keys
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->persist();
        $folder = FolderFactory::make([
            'name' => 'foo',
        ])->persist();

        $uac = $this->mockAdminAccessControl();
        $metadataForF1 = $this->encryptForMetadataKey(json_encode([]));
        $data = [
            [
                'id' => $folder->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataForF1,
                'modified' => $folder->get('modified'),
                'modified_by' => $folder->get('modified_by'),
            ],
        ];
        $this->service->updateMany($uac, $data);
        $folderUpdated = FolderFactory::firstOrFail();
        $this->assertNull($folderUpdated->name);
    }
}
