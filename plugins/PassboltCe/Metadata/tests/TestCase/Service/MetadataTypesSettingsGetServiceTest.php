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

namespace Passbolt\Metadata\Test\TestCase\Service;

use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Lib\AppTestCaseV5;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;
use Passbolt\Metadata\Service\MetadataTypesSettingsGetService;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;

class MetadataTypesSettingsGetServiceTest extends AppTestCaseV5
{
    public function testMetadataTypesSettingsGetService_getSettings_NotEntryReturnsDefault(): void
    {
        $settings = MetadataTypesSettingsGetService::getSettings();
        $this->assertEquals(MetadataTypesSettingsFactory::getDefaultDataV4(), $settings->toArray());
    }

    public function testMetadataTypesSettingsGetService_getSettings_NotDefault(): void
    {
        $data = MetadataTypesSettingsFactory::getDefaultDataV4();
        $data[MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE] = 'v5';
        $data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS] = false;
        $data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS] = true;
        $data[MetadataTypesSettingsDto::ALLOW_V5_V4_DOWNGRADE] = true;
        $data[MetadataTypesSettingsDto::ALLOW_V4_V5_UPGRADE] = true;
        MetadataTypesSettingsFactory::make()->value(json_encode($data))->persist();
        $settings = MetadataTypesSettingsGetService::getSettings();
        $this->assertEquals($data, $settings->toArray());
    }

    public function testMetadataTypesSettingsGetService_getSettings_BrokenSettingsReturnsDefault(): void
    {
        $this->assertEquals(0, MetadataTypesSettingsFactory::count());
        $data = MetadataTypesSettingsFactory::getDefaultDataV4();
        $data[MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS] = '🔥';
        $data[MetadataTypesSettingsDto::ALLOW_V4_V5_UPGRADE] = [];
        MetadataTypesSettingsFactory::make()->value(json_encode($data))->persist();
        $settings = MetadataTypesSettingsGetService::getSettings();
        $this->assertEquals(MetadataTypesSettingsFactory::getDefaultDataV4(), $settings->toArray());
    }

    public function testMetadataTypesSettingsGetService_getSettings_BrokenJsonSettingsReturnsDefault(): void
    {
        OrganizationSettingFactory::make()
            ->setPropertyAndValue(MetadataTypesSettingsGetService::ORG_SETTING_PROPERTY, '🔥')
            ->persist();
        $settings = MetadataTypesSettingsGetService::getSettings();
        $this->assertEquals(MetadataTypesSettingsFactory::getDefaultDataV4(), $settings->toArray());
    }

    /**
     * @dataProvider creationAllowedMethodsDataProvider
     * @param array $settingsValues Settings values for arrange part.
     * @param array $assertions Assertions to perform.
     * @return void
     */
    public function testMetadataTypesSettingsGetService_CreationAllowedMethods(array $settingsValues, array $assertions): void
    {
        $data = MetadataTypesSettingsFactory::getDefaultDataV4();
        foreach ($settingsValues as $settingField => $settingValue) {
            $data[$settingField] = $settingValue;
        }
        MetadataTypesSettingsFactory::make()->value(json_encode($data))->persist();

        $settings = MetadataTypesSettingsGetService::getSettings();

        foreach ($assertions as $method => $result) {
            $this->assertSame($result, $settings->{$method}());
        }
    }

    /**
     * Data provider.
     *
     * @return array[]
     */
    public static function creationAllowedMethodsDataProvider(): array
    {
        return [
            [
                'input' => [
                    MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES => MetadataTypesSettingsDto::V5,
                    MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE => MetadataTypesSettingsDto::V4,
                    MetadataTypesSettingsDto::DEFAULT_TAG_TYPE => MetadataTypesSettingsDto::V5,
                    MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES => false,
                    MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => true,
                    MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS => true,
                    MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES => true,
                    MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => false,
                    MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS => true,
                ],
                'expected' => [
                    'isV4ResourceCreationAllowed' => false,
                    'isV4FolderCreationAllowed' => true,
                    'isV4TagCreationAllowed' => true,
                    'isV5ResourceCreationAllowed' => true,
                    'isV5FolderCreationAllowed' => false,
                    'isV5TagCreationAllowed' => true,
                ],
            ],
            // More combinations goes here
        ];
    }
}
