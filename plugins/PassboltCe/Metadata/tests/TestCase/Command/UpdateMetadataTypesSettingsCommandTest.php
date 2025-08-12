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

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Metadata\Command\UpdateMetadataTypesSettingsCommand
 */
class UpdateMetadataTypesSettingsCommandTest extends AppIntegrationTestCaseV5
{
    use ConsoleIntegrationTestTrait;
    use GpgMetadataKeysTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function testUpdateMetadataTypesSettingsCommand_Help()
    {
        $this->exec('passbolt metadata update_metadata_types_settings -h');

        $this->assertExitSuccess();
        $this->assertOutputContains('Create/update metadata types settings.');
        $this->assertOutputContains('cake passbolt metadata update_metadata_types_settings');
    }

    public function testUpdateMetadataTypesSettingsCommand_Success_UpgradeResourcesToV5(): void
    {
        // Create an active metadata key
        MetadataKeyFactory::make()->persist();
        $user = UserFactory::make()->active()->persist();
        MetadataTypesSettingsFactory::make()->v4()->persist();

        $optionsArray = [
            sprintf('--%s=%s', MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES, MetadataTypesSettingsDto::V5),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES, '1'),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES, '1'),
        ];
        $optionsArray[] = sprintf('--username=%s', $user->get('username'));
        $options = implode(' ', $optionsArray);

        $this->exec("passbolt metadata update_metadata_types_settings {$options}");

        $this->assertExitSuccess('Metadata types settings have been updated');
        /** @var \App\Model\Entity\OrganizationSetting $settings */
        $settings = MetadataTypesSettingsFactory::firstOrFail();
        $settingsArray = json_decode($settings->value, true);
        $this->assertSame(MetadataTypesSettingsDto::V5, $settingsArray[MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES]);
        $this->assertTrue($settingsArray[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES]);
        $this->assertTrue($settingsArray[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES]);
    }

    public function testUpdateMetadataTypesSettingsCommand_Success_UpgradeFoldersToV5(): void
    {
        // Create an active metadata key
        MetadataKeyFactory::make()->persist();
        $user = UserFactory::make()->active()->persist();
        /** @var \App\Model\Entity\OrganizationSetting $metadataSettings */
        $metadataSettings = MetadataTypesSettingsFactory::make()->v4()->persist();
        $oldSettings = json_decode($metadataSettings->value, true);

        $optionsArray = [
            sprintf('--%s=%s', MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE, MetadataTypesSettingsDto::V5),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS, '1'),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS, '0'),
        ];
        $optionsArray[] = sprintf('--username=%s', $user->get('username'));
        $options = implode(' ', $optionsArray);

        $this->exec("passbolt metadata update_metadata_types_settings {$options}");

        $this->assertExitSuccess('Metadata types settings have been updated');
        /** @var \App\Model\Entity\OrganizationSetting $updatedSettings */
        $updatedSettings = MetadataTypesSettingsFactory::firstOrFail();
        $updatedSettings = json_decode($updatedSettings->value, true);
        $this->assertSame(MetadataTypesSettingsDto::V5, $updatedSettings[MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE]);
        $this->assertTrue($updatedSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS]);
        $this->assertFalse($updatedSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS]);
        // Make sure existing values are not updated
        $this->assertSame($oldSettings[MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES], $updatedSettings[MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES]);
        $this->assertSame($oldSettings[MetadataTypesSettingsDto::DEFAULT_TAG_TYPE], $updatedSettings[MetadataTypesSettingsDto::DEFAULT_TAG_TYPE]);
        $this->assertSame($oldSettings[MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE], $updatedSettings[MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE]);
        $this->assertSame($oldSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES], $updatedSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES]);
        $this->assertSame($oldSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS], $updatedSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS]);
        $this->assertSame($oldSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS], $updatedSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS]);
        $this->assertSame($oldSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES], $updatedSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES]);
        $this->assertSame($oldSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS], $updatedSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS]);
        $this->assertSame($oldSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS], $updatedSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS]);
    }

    public function testUpdateMetadataTypesSettingsCommand_Success_UpgradeTagsToV5(): void
    {
        // Create an active metadata key
        MetadataKeyFactory::make()->persist();
        $user = UserFactory::make()->active()->persist();
        MetadataTypesSettingsFactory::make()->v4()->persist();

        $optionsArray = [
            sprintf('--%s=%s', MetadataTypesSettingsDto::DEFAULT_TAG_TYPE, MetadataTypesSettingsDto::V5),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS, '1'),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS, '0'),
        ];
        $optionsArray[] = sprintf('--username=%s', $user->get('username'));
        $options = implode(' ', $optionsArray);

        $this->exec("passbolt metadata update_metadata_types_settings {$options}");

        $this->assertExitSuccess('Metadata types settings have been updated');
        /** @var \App\Model\Entity\OrganizationSetting $updatedSettings */
        $updatedSettings = MetadataTypesSettingsFactory::firstOrFail();
        $updatedSettings = json_decode($updatedSettings->value, true);
        $this->assertSame(MetadataTypesSettingsDto::V5, $updatedSettings[MetadataTypesSettingsDto::DEFAULT_TAG_TYPE]);
        $this->assertTrue($updatedSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS]);
        $this->assertFalse($updatedSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS]);
    }

    public function testUpdateMetadataTypesSettingsCommand_Success_SettingsCreated(): void
    {
        // Create an active metadata key
        MetadataKeyFactory::make()->persist();
        $user = UserFactory::make()->active()->persist();

        $optionsArray = [
            sprintf('--%s=%s', MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES, MetadataTypesSettingsDto::V5),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES, '1'),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES, '0'),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_V5_V4_DOWNGRADE, '1'),
        ];
        $optionsArray[] = sprintf('--username=%s', $user->get('username'));
        $options = implode(' ', $optionsArray);

        $this->exec("passbolt metadata update_metadata_types_settings {$options}");

        $this->assertExitSuccess('Metadata types settings have been updated');
        /** @var \App\Model\Entity\OrganizationSetting[] $updatedSettings */
        $updatedSettings = MetadataTypesSettingsFactory::find()->toArray();
        $this->assertCount(1, $updatedSettings);
        $updatedSetting = $updatedSettings[0];
        $updatedSetting = json_decode($updatedSetting->value, true);
        $this->assertSame(MetadataTypesSettingsDto::V5, $updatedSetting[MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES]);
        $this->assertTrue($updatedSetting[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES]);
        $this->assertFalse($updatedSetting[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES]);
        $this->assertTrue($updatedSetting[MetadataTypesSettingsDto::ALLOW_V5_V4_DOWNGRADE]);
    }

    public function testUpdateMetadataTypesSettingsCommand_Success_DowngradeResourcesToV4(): void
    {
        $user = UserFactory::make()->active()->persist();
        MetadataTypesSettingsFactory::make()->v5()->persist();

        $optionsArray = [
            sprintf('--%s=%s', MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES, MetadataTypesSettingsDto::V4),
            sprintf('--%s=%s', MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE, MetadataTypesSettingsDto::V4),
            sprintf('--%s=%s', MetadataTypesSettingsDto::DEFAULT_TAG_TYPE, MetadataTypesSettingsDto::V4),
            sprintf('--%s=%s', MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE, MetadataTypesSettingsDto::V4),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES, '1'),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES, '0'),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS, '0'),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS, '0'),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS, '0'),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_V4_V5_UPGRADE, '0'),
        ];
        $optionsArray[] = sprintf('--username=%s', $user->get('username'));
        $options = implode(' ', $optionsArray);

        $this->exec("passbolt metadata update_metadata_types_settings {$options}");

        $this->assertExitSuccess('Metadata types settings have been updated');
        /** @var \App\Model\Entity\OrganizationSetting $updatedSettings */
        $updatedSettings = MetadataTypesSettingsFactory::firstOrFail();
        $updatedSettings = json_decode($updatedSettings->value, true);
        $this->assertSame(MetadataTypesSettingsDto::V4, $updatedSettings[MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES]);
        $this->assertTrue($updatedSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES]);
        $this->assertFalse($updatedSettings[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES]);
    }

    public function testUpdateMetadataTypesSettingsCommand_Error_UserNotFound(): void
    {
        $optionsArray = [
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES, '1'),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES, '1'),
        ];
        $optionsArray[] = '--username=notfound@test.test';
        $options = implode(' ', $optionsArray);

        $this->exec("passbolt metadata update_metadata_types_settings {$options}");

        $this->assertExitError();
        $this->assertErrorContains('The user does not exist or is not active or is disabled');
    }

    public function testUpdateMetadataTypesSettingsCommand_Error_Validation(): void
    {
        $user = UserFactory::make()->active()->persist();
        /** @var \App\Model\Entity\OrganizationSetting $oldSettings */
        $oldSettings = MetadataTypesSettingsFactory::make()->v5()->persist();
        $oldSettings = json_decode($oldSettings->value, true);

        $optionsArray = [
            sprintf('--%s=%s', MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES, 'v1'),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES, 'foo-bar'),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES, '🔥'),
            sprintf('--%s=%f', MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE, 9.99),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS, '888'),
            sprintf('--%s=%s', MetadataTypesSettingsDto::ALLOW_V5_V4_DOWNGRADE, 108),
        ];
        $optionsArray[] = sprintf('--username=%s', $user->get('username'));
        $options = implode(' ', $optionsArray);

        $this->exec("passbolt metadata update_metadata_types_settings {$options}");

        $this->assertExitError();
        $this->assertOutputContains('Unable to update metadata types settings. See validation errors:');
        $this->assertOutputContains('Error field');
        $this->assertOutputContains('Error message');
        $this->assertOutputContains('default_resource_types');
        $this->assertOutputContains('The setting should be one of the following: v4, v5');
        $this->assertOutputContains('allow_creation_of_v5_resources');
        $this->assertOutputContains('allow_creation_of_v5_folders');
        $this->assertOutputContains('allow_creation_of_v4_resources');
        $this->assertOutputContains('allow_v5_v4_downgrade');
        $this->assertOutputContains('The setting should be a valid boolean');
        /** @var \App\Model\Entity\OrganizationSetting $updatedSettings */
        $updatedSettings = MetadataTypesSettingsFactory::firstOrFail();
        $updatedSettings = json_decode($updatedSettings->value, true);
        // no update
        $this->assertArrayEqualsCanonicalizing($oldSettings, $updatedSettings);
    }

    public function testUpdateMetadataTypesSettingsCommand_Error_NoDataProvided(): void
    {
        $user = UserFactory::make()->active()->persist();

        $this->exec('passbolt metadata update_metadata_types_settings --username=' . $user->get('username'));

        $this->assertExitError();
        $this->assertErrorContains('No metadata types settings provided. Please check available options');
    }
}
