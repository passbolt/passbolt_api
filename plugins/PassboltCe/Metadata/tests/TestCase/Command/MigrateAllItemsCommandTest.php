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
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;
use Passbolt\Metadata\Service\Migration\MigrateAllV4ToV5ServiceCollector;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Metadata\Test\Utility\MigrateFoldersTestTrait;
use Passbolt\Metadata\Test\Utility\MigrateResourcesTestTrait;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * @covers \Passbolt\Metadata\Command\MigrateAllItemsCommand
 */
class MigrateAllItemsCommandTest extends AppIntegrationTestCaseV5
{
    use ConsoleIntegrationTestTrait;
    use GpgMetadataKeysTestTrait;
    use MigrateFoldersTestTrait;
    use MigrateResourcesTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        // clear collector state to get proper results
        MigrateAllV4ToV5ServiceCollector::clear();
    }

    public function testMigrateAllItemsCommand_Help()
    {
        $this->exec('passbolt metadata migrate_all_items -h');

        $this->assertExitSuccess();
        $this->assertOutputContains('Migrate V4 resources, folders, etc. to V5.');
        $this->assertOutputContains('cake passbolt metadata migrate_all_items');
    }

    public function testMigrateAllItemsCommand_Success(): void
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
        // Create resources
        $totpStandalone = ResourceTypeFactory::make()->standaloneTotp()->persist();
        ResourceTypeFactory::make()->v5StandaloneTotp()->persist();
        $personalResource = ResourceFactory::make()
            ->with('ResourceTypes', $totpStandalone)
            ->withCreatorAndPermission($ada)
            ->persist();
        $sharedResource = ResourceFactory::make()
            ->with('ResourceTypes', $totpStandalone)
            ->withCreatorAndPermission($ada)
            ->persist();
        PermissionFactory::make()->acoResource($sharedResource)->typeRead()->aroUser($maki)->persist();
        // Create folders
        $personalFolder = FolderFactory::make()->withFoldersRelationsFor([$maki])->withPermissionsFor([$maki])->persist();
        $sharedFolder = FolderFactory::make()->withFoldersRelationsFor([$ada, $maki])->withPermissionsFor([$ada, $maki])->persist();

        $this->exec('passbolt metadata migrate_all_items');

        $this->assertExitSuccess();
        $this->assertOutputContains('All items successfully migrated.');
        $this->assertOutputContains('See migrated items summary below:');
        $this->assertOutputContains('Entity');
        $this->assertOutputContains('Number of rows updated');
        $this->assertOutputContains('resources');
        $this->assertOutputContains('folders');
        $this->assertOutputContains('2');
        $this->assertOutputContains('Total');
        $this->assertOutputContains('4');
        // Resources assertions
        $updatedPersonalResource = ResourceFactory::get($personalResource->get('id'));
        $this->assertionsForPersonalResource($updatedPersonalResource, $personalResource, $ada->gpgkey, [
            'private_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key'),
            'passphrase' => '',
        ]);
        $updatedSharedResource = ResourceFactory::get($sharedResource->get('id'));
        $this->assertionsForSharedResource($updatedSharedResource, $sharedResource, $metadataKey);
        // Folders assertions
        $updatedPersonalFolder = FolderFactory::get($personalFolder->get('id'));
        $makiKeyInfo = $this->getUserKeyInfo();
        $this->assertionsForPersonalFolder($updatedPersonalFolder, $personalFolder, $maki->gpgkey, $makiKeyInfo);
        $updatedSharedResource = FolderFactory::get($sharedFolder->get('id'));
        $this->assertionsForSharedFolder($updatedSharedResource, $sharedFolder, $metadataKey);
    }

    public function testMigrateAllItemsCommand_Error_PartialFailures(): void
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
        // note: do not create metadata key to get the error
        // Create a resource
        $totpStandalone = ResourceTypeFactory::make()->standaloneTotp()->persist();
        ResourceTypeFactory::make()->v5StandaloneTotp()->persist();
        $personalResource = ResourceFactory::make()
            ->with('ResourceTypes', $totpStandalone)
            ->withCreatorAndPermission($ada)
            ->persist();
        // Create folders
        FolderFactory::make()->withFoldersRelationsFor([$ada, $maki])->withPermissionsFor([$ada, $maki])->persist();

        $this->exec('passbolt metadata migrate_all_items');

        $this->assertExitError();
        $this->assertErrorContains('There were few errors while migrating some items.');
        $this->assertErrorContains('See errors below:');
        $this->assertOutputContains('Entity');
        $this->assertOutputContains('Error message');
        $this->assertOutputContains('folders');
        $this->assertOutputContains('Record not found in table `metadata_keys`');
        $this->assertOutputContains('See migrated items summary below');
        $this->assertOutputContains('Total');
        $this->assertOutputContains('1');
        // Check db is updated
        $updatedResource = ResourceFactory::get($personalResource->get('id'));
        $this->assertionsForPersonalResource($updatedResource, $personalResource, $ada->gpgkey, [
            'private_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key'),
            'passphrase' => '',
        ]);
    }

    public function testMigrateAllItemsCommand_Error_NoItems(): void
    {
        MetadataTypesSettingsFactory::make()->v5()->persist();

        $this->exec('passbolt metadata migrate_all_items');

        $this->assertExitError();
        $this->assertErrorContains('No items were migrated due to errors');
        $this->assertErrorContains('See errors below:');
        $this->assertOutputContains('No resources to migrate');
        $this->assertOutputContains('No folders to migrate');
    }

    public function testMigrateAllItemsCommand_Error_CreationOfV5ResourcesDisabled(): void
    {
        $value = array_merge(MetadataTypesSettingsFactory::getDefaultDataV4(), [
            MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE => MetadataTypesSettingsDto::V5,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => false,
        ]);
        MetadataTypesSettingsFactory::make()->value($value)->persist();
        /** @var \App\Model\Entity\User $ada */
        $ada = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        // Create resources
        $totpStandalone = ResourceTypeFactory::make()->standaloneTotp()->persist();
        ResourceTypeFactory::make()->v5StandaloneTotp()->persist();
        ResourceFactory::make()
            ->with('ResourceTypes', $totpStandalone)
            ->withCreatorAndPermission($ada)
            ->persist();
        // Create folders
        FolderFactory::make()->withFoldersRelationsFor([$ada])->withPermissionsFor([$ada])->persist();

        $this->exec('passbolt metadata migrate_all_items');

        $this->assertExitError();
        $this->assertErrorContains('There were few errors while migrating some items.');
        $this->assertErrorContains('See errors below:');
        $this->assertOutputContains('Resource creation/modification with encrypted metadata not allowed');
        $this->assertOutputContains('See migrated items summary below:');
        $this->assertOutputContains('Entity');
        $this->assertOutputContains('Number of rows updated');
        $this->assertOutputContains('folders');
        $this->assertOutputContains('Total');
        $this->assertOutputContains('1');
    }
}
