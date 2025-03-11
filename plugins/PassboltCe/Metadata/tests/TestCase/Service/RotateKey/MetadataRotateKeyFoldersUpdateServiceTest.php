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

namespace Passbolt\Metadata\Test\TestCase\Service\RotateKey;

use App\Error\Exception\CustomValidationException;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ConflictException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenTime;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Metadata\Model\Dto\MetadataFolderDto;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Service\RotateKey\MetadataRotateKeyFoldersUpdateService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Metadata\Service\RotateKey\MetadataRotateKeyFoldersUpdateService
 */
class MetadataRotateKeyFoldersUpdateServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    /**
     * @var MetadataRotateKeyFoldersUpdateService|null
     */
    private ?MetadataRotateKeyFoldersUpdateService $service = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new MetadataRotateKeyFoldersUpdateService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMetadataRotateKeyFoldersUpdateService_Success(): void
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
        $metadata = json_encode(MetadataFolderDto::fromArray(['name' => 'marketing'])->getClearTextMetadata());
        $expiredFolder1 = FolderFactory::make()
            ->withPermissionsFor([$admin])
            ->withFoldersRelationsFor([$admin])
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();
        // another user's resource returned
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($user->get('gpgkey'))->persist();
        $metadata = json_encode(MetadataFolderDto::fromArray(['name' => 'customer support'])->getClearTextMetadata());
        $expiredFolder2 = FolderFactory::make()
            ->withPermissionsFor([$admin])
            ->withFoldersRelationsFor([$admin])
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();

        $uac = $this->mockAdminAccessControl();
        $metadataToUpdateForF1 = $this->encryptForMetadataKey(json_encode(MetadataFolderDto::fromArray(['name' => 'f1 marketing updated'])->getClearTextMetadata()));
        $metadataToUpdateForF2 = $this->encryptForMetadataKey(json_encode(MetadataFolderDto::fromArray(['name' => 'f1 customer support updated'])->getClearTextMetadata()));
        $data = [
            [
                'id' => $expiredFolder1->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataToUpdateForF1,
                'modified' => $expiredFolder1->get('modified')->format('Y-m-d H:i:s'),
                'modified_by' => $expiredFolder1->get('modified_by'),
            ],
            [
                'id' => $expiredFolder2->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataToUpdateForF2,
                'modified' => $expiredFolder2->get('modified')->format('Y-m-d H:i:s'),
                'modified_by' => $expiredFolder2->get('modified_by'),
            ],
        ];
        $this->service->updateMany($uac, $data);

        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder1 */
        $updatedFolder1 = FolderFactory::get($expiredFolder1->get('id'));
        $this->assertSame($activeMetadataKey->get('id'), $updatedFolder1->get('metadata_key_id'));
        $this->assertSame($metadataToUpdateForF1, $updatedFolder1->get('metadata'));
        $this->assertSame(Chronos::now()->format('Y-m-d H:i'), $updatedFolder1->get('modified')->format('Y-m-d H:i')); // comparing seconds here might fail
        $this->assertSame($uac->getId(), $updatedFolder1->get('modified_by'));
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder2 */
        $updatedFolder2 = FolderFactory::get($expiredFolder2->get('id'));
        $this->assertSame($activeMetadataKey->get('id'), $updatedFolder2->get('metadata_key_id'));
        $this->assertSame($metadataToUpdateForF2, $updatedFolder2->get('metadata'));
        $this->assertSame(Chronos::now()->format('Y-m-d H:i'), $updatedFolder2->get('modified')->format('Y-m-d H:i'));
        $this->assertSame($uac->getId(), $updatedFolder2->get('modified_by'));
    }

    public function testMetadataRotateKeyFoldersUpdateService_Error_EmptyData(): void
    {
        $uac = $this->mockAdminAccessControl();
        $this->expectException(BadRequestException::class);
        $this->service->updateMany($uac, []);
    }

    public function testMetadataRotateKeyFoldersUpdateService_Error_DataIsNotAnArray(): void
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
    public function testMetadataRotateKeyFoldersUpdateService_Error_InvalidMetadataEncryptedMessage($metadata): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        $folder = FolderFactory::make()
            ->withPermissionsFor([$admin])
            ->withFoldersRelationsFor([$admin])
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();
        $uac = $this->makeUac($admin);
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

    public function testMetadataRotateKeyFoldersUpdateService_Error_FolderNotFound(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $uac = $this->makeUac($admin);
        $metadata = json_encode(MetadataFolderDto::fromArray(['name' => 'marketing'])->getClearTextMetadata());
        $data = [
            [
                'id' => UuidFactory::uuid(),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForMetadataKey($metadata),
                'modified' => \Cake\I18n\DateTime::now(),
                'modified_by' => $admin->get('id'),
            ],
        ];

        $this->expectException(NotFoundException::class);
        $this->service->updateMany($uac, $data);
    }

    public function testMetadataRotateKeyFoldersUpdateService_Error_ModifiedDateConflict(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        $metadata = json_encode(MetadataFolderDto::fromArray(['name' => 'marketing'])->getClearTextMetadata());
        $folder = FolderFactory::make()
            ->withPermissionsFor([$admin])
            ->withFoldersRelationsFor([$admin])
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $uac = $this->makeUac($admin);
        $data = [
            [
                'id' => $folder->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
                'modified' => \Cake\I18n\DateTime::now(),
                'modified_by' => $admin->get('id'),
            ],
        ];

        $this->expectException(ConflictException::class);
        $this->service->updateMany($uac, $data);
    }

    public function testMetadataRotateKeyFoldersUpdateService_Error_ModifiedByConflict(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        $metadata = json_encode(MetadataFolderDto::fromArray(['name' => 'marketing'])->getClearTextMetadata());
        $folder = FolderFactory::make()
            ->withPermissionsFor([$admin])
            ->withFoldersRelationsFor([$admin])
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $uac = $this->makeUac($admin);
        $data = [
            [
                'id' => $folder->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
                'modified' => $folder->get('modified'),
                'modified_by' => UuidFactory::uuid(),
            ],
        ];

        $this->expectException(ConflictException::class);
        $this->service->updateMany($uac, $data);
    }

    public function testMetadataRotateKeyFoldersUpdateService_Error_MetadataKeyIsExpired(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        $metadata = json_encode(MetadataFolderDto::fromArray(['name' => 'marketing'])->getClearTextMetadata());
        $folder = FolderFactory::make()
            ->withPermissionsFor([$admin])
            ->withFoldersRelationsFor([$admin])
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();

        try {
            $uac = $this->mockAdminAccessControl();
            $metadataToUpdate = json_encode(MetadataFolderDto::fromArray(['name' => 'marketing - updated'])->getClearTextMetadata());
            $data = [
                [
                    'id' => $folder->get('id'),
                    'metadata_key_id' => $expiredMetadataKey->get('id'),
                    'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                    'metadata' => $this->encryptForMetadataKey($metadataToUpdate),
                    'modified' => $folder->get('modified'),
                    'modified_by' => $folder->get('modified_by'),
                ],
            ];
            $this->service->updateMany($uac, $data);
        } catch (\Exception $e) {
            $this->assertInstanceOf(CustomValidationException::class, $e);
            $errors = $e->getErrors();
            $this->assertArrayHasKey('isMetadataKeyNotExpired', $errors[0]['metadata_key_id']);
        }
    }

    public function testMetadataRotateKeyFoldersUpdateService_Error_Multiple_Active_Metadata_Keys(): void
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
        $metadata = json_encode(MetadataFolderDto::fromArray(['name' => 'marketing'])->getClearTextMetadata());
        $folder = FolderFactory::make()
            ->withPermissionsFor([$admin])
            ->withFoldersRelationsFor([$admin])
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();

        $uac = $this->mockAdminAccessControl();
        $metadataToUpdateForF1 = $this->encryptForMetadataKey(json_encode(MetadataFolderDto::fromArray(['name' => 'f1 marketing updated'])->getClearTextMetadata()));
        $data = [
            [
                'id' => $folder->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataToUpdateForF1,
                'modified' => $folder->get('modified')->format('Y-m-d H:i:s'),
                'modified_by' => $folder->get('modified_by'),
            ],
        ];
        try {
            $this->service->updateMany($uac, $data);
        } catch (\Exception $e) {
            $this->assertInstanceOf(CustomValidationException::class, $e);
            $errors = $e->getErrors();
            $this->assertArrayHasKey('isSharedMetadataKeyUniqueActive', $errors[0]['metadata_key_id']);
        }
    }
}
