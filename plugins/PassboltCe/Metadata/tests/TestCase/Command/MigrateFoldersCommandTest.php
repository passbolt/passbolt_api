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
namespace Passbolt\Metadata\Test\TestCase\Command;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Metadata\Test\Utility\MigrateFoldersTestTrait;

/**
 * @covers \Passbolt\Metadata\Command\MigrateFoldersCommand
 */
class MigrateFoldersCommandTest extends AppIntegrationTestCaseV5
{
    use ConsoleIntegrationTestTrait;
    use GpgMetadataKeysTestTrait;
    use MigrateFoldersTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->useCommandRunner();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function testMigrateFoldersCommand_Help()
    {
        $this->exec('passbolt metadata migrate_folders -h');

        $this->assertExitSuccess();
        $this->assertOutputContains('Migrate V4 folders to V5.');
        $this->assertOutputContains('cake passbolt metadata migrate_folders');
    }

    public function testMigrateFoldersCommand_Success_MultipleFolders(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        $makiKeyInfo = $this->getUserKeyInfo();
        /** @var \App\Model\Entity\User $maki */
        $maki = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withKeyInfo($makiKeyInfo))
            ->user()
            ->active()
            ->persist();
        // create metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($ada)->withServerPrivateKey()->persist();
        // Personal folder of Ada
        $folder1 = FolderFactory::make()->withFoldersRelationsFor([$ada])->withPermissionsFor([$ada])->persist();
        // Personal folder of Maki
        $folder2 = FolderFactory::make()->withFoldersRelationsFor([$maki])->withPermissionsFor([$maki])->persist();
        // Shared folder main (parent)
        $folder3 = FolderFactory::make()->withFoldersRelationsFor([$ada, $maki])->withPermissionsFor([$ada, $maki])->persist();
        // Shared child folder (level-2)
        $folder4 = FolderFactory::make()->withFoldersRelationsFor([$ada, $maki], $folder3)->withPermissionsFor([$ada, $maki])->persist();
        // Personal child folder of Maki (level-3)
        $folder5 = FolderFactory::make()->withFoldersRelationsFor([$maki], $folder4)->withPermissionsFor([$maki])->persist();

        $this->exec('passbolt metadata migrate_folders');

        $this->assertExitSuccess();
        /** @var \App\Model\Entity\Resource[] $updatedFolders */
        $updatedFolders = FolderFactory::find()->toArray();
        $this->assertCount(5, $updatedFolders);
        // Personal folder of Ada
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder1 */
        $updatedFolder1 = FolderFactory::get($folder1->get('id'));
        $this->assertionsForPersonalFolder($updatedFolder1, $folder1, $ada->gpgkey, [
            'private_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key'),
            'passphrase' => '',
        ]);
        // Personal folder of Betty
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder2 */
        $updatedFolder2 = FolderFactory::get($folder2->get('id'));
        $this->assertionsForPersonalFolder($updatedFolder2, $folder2, $maki->gpgkey, $makiKeyInfo);
        // Shared folder main (parent)
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder3 */
        $updatedFolder3 = FolderFactory::get($folder3->get('id'));
        $this->assertionsForSharedFolder($updatedFolder3, $folder3, $metadataKey);
        // Shared child folder (level-2)
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder4 */
        $updatedFolder4 = FolderFactory::get($folder4->get('id'));
        $this->assertionsForSharedFolder($updatedFolder4, $folder4, $metadataKey);
        // Personal child folder of Maki (level-3)
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder5 */
        $updatedFolder5 = FolderFactory::get($folder5->get('id'));
        $this->assertionsForPersonalFolder($updatedFolder5, $folder5, $maki->gpgkey, $makiKeyInfo);
    }

    public function testMigrateFoldersCommand_Error_PartialFailures(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        // Create a personal folder of Ada
        $personalFolder = FolderFactory::make()->withFoldersRelationsFor([$ada])->withPermissionsFor([$ada])->persist();
        // Create a shared folder
        $makiKeyInfo = $this->getUserKeyInfo();
        /** @var \App\Model\Entity\User $maki */
        $maki = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withKeyInfo($makiKeyInfo))
            ->user()
            ->active()
            ->persist();
        FolderFactory::make()->withFoldersRelationsFor([$ada, $maki])->withPermissionsFor([$ada, $maki])->persist();

        $this->exec('passbolt metadata migrate_folders');

        $this->assertExitError();
        $this->assertOutputContains('<success>1 folders were migrated.</success>');
        $this->assertOutputContains('All folders could not migrated.');
        $this->assertOutputContains('See errors:');
        $this->assertOutputContains('Record not found in table "metadata_keys"');
        /** @var \Passbolt\Folders\Model\Entity\Folder $updatedFolder */
        $updatedFolder = FolderFactory::get($personalFolder->get('id'));
        $this->assertionsForPersonalFolder($updatedFolder, $personalFolder, $ada->gpgkey, [
            'private_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key'),
            'passphrase' => '',
        ]);
    }

    public function testMigrateFoldersCommand_Error_NoFolders(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();

        $this->exec('passbolt metadata migrate_folders');

        $this->assertExitError();
        $this->assertOutputContains('All folders could not migrated.');
        $this->assertOutputContains('See errors:');
        $this->assertOutputContains('No folders to migrate.');
    }

    public function testMigrateFoldersCommand_Error_CreationOfV5FoldersIsDisabled(): void
    {
        MetadataTypesSettingsFactory::make()->v4()->persist();

        $this->exec('passbolt metadata migrate_folders');

        $this->assertExitError();
        $this->assertErrorContains('Folder creation/modification with encrypted metadata not allowed.');
        $this->assertErrorContains('To enable, set "allow_creation_of_v5_resources" metadata settings to true via `update_metadata_types_settings` command');
    }
}
