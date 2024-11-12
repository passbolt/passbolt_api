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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Test\TestCase\Service\Folders;

use App\Error\Exception\ValidationException;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Service\Folders\FoldersUpdateService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Metadata\Event\MetadataFolderUpdateListener;
use Passbolt\Metadata\Model\Dto\MetadataFolderDto;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Service\MetadataTypesSettingsGetService;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Folders\Service\Folders\FoldersUpdateService
 */
class MetadataFoldersUpdateServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    private FoldersUpdateService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new FoldersUpdateService();
        // clear state
        MetadataTypesSettingsGetService::clear();
        // Enable event tracking
        EventManager::instance()->setEventList(new EventList());
        // Attach listener manually for testing purpose
        EventManager::instance()->on(new MetadataFolderUpdateListener());
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMetadataFoldersUpdateService_Success()
    {
        // Enable v5 settings
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user])
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        // prepare data to update
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing updated']);
        $updatedMetadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        $folderDto = new MetadataFolderDto(
            null,
            null,
            $updatedMetadata,
            $user->gpgkey->id,
            MetadataKey::TYPE_USER_KEY
        );

        $result = $this->service->update($this->makeUac($user), $folder->get('id'), $folderDto);

        $this->assertInstanceOf(Folder::class, $result);
        $this->assertSame(1, FolderFactory::count());
    }

    public function testMetadataFoldersUpdateService_Success_V5ToV4DowngradeAllowed()
    {
        // allow downgrade in settings
        $settings = MetadataTypesSettingsGetService::defaultV4Settings();
        $settings[MetadataTypesSettingsDto::ALLOW_V5_V4_DOWNGRADE] = true;
        MetadataTypesSettingsFactory::make()->value($settings)->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user])
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        // v4 folder DTO
        $folderDto = new MetadataFolderDto('marketing updated');

        $result = $this->service->update($this->makeUac($user), $folder->get('id'), $folderDto);

        $this->assertInstanceOf(Folder::class, $result);
        $folders = FolderFactory::find()->toArray();
        $this->assertCount(1, $folders);
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = $folders[0];
        $this->assertSame('marketing updated', $folder->get('name'));
        $this->assertNull($folder->get('metadata'));
        $this->assertNull($folder->get('metadata_key_id'));
        $this->assertNull($folder->get('metadata_key_type'));
    }

    public function testMetadataFoldersUpdateService_Error_V5ToV4DowngradeNotAllowed()
    {
        // Enable v5 settings
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $clearTextMetadata = json_encode(['object_type' => 'PASSBOLT_FOLDER_METADATA', 'name' => 'marketing']);
        $metadata = $this->encryptForUser($clearTextMetadata, $user, $this->getAdaNoPassphraseKeyInfo());
        /** @var \Passbolt\Folders\Model\Entity\Folder $folder */
        $folder = FolderFactory::make()
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user])
            ->v5Fields(['metadata' => $metadata, 'metadata_key_id' => $user->gpgkey->id])
            ->persist();
        // v4 folder DTO
        $folderDto = new MetadataFolderDto('marketing updated');

        try {
            $this->service->update($this->makeUac($user), $folder->get('id'), $folderDto);
        } catch (ValidationException $e) {
            $this->assertStringContainsString('Could not validate folder data', $e->getMessage());
            $errors = $e->getErrors();
            $this->assertArrayHasKey('v5_to_v4_downgrade_allowed', $errors['name']);
        }
    }
}
