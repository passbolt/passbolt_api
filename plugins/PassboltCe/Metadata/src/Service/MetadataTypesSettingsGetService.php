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
namespace Passbolt\Metadata\Service;

use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\TableRegistry;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;

class MetadataTypesSettingsGetService
{
    use LocatorAwareTrait;

    public const ORG_SETTING_PROPERTY = 'metadataTypes';

    /**
     * @var \Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto|null
     */
    private static ?MetadataTypesSettingsDto $settings = null;

    /**
     * TODO for v5 it should default to v5
     *
     * @return \Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto default settings when there is none set
     */
    public static function getDefaultSettings(): MetadataTypesSettingsDto
    {
        return new MetadataTypesSettingsDto(self::defaultV4Settings());
    }

    /**
     * @return array
     */
    public static function defaultV4Settings(): array
    {
        return [
            MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES => MetadataTypesSettingsDto::V4,
            MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE => MetadataTypesSettingsDto::V4,
            MetadataTypesSettingsDto::DEFAULT_TAG_TYPE => MetadataTypesSettingsDto::V4,
            MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE => MetadataTypesSettingsDto::V4,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS => true,
            MetadataTypesSettingsDto::ALLOW_V5_V4_DOWNGRADE => false,
        ];
    }

    /**
     * Read the metadata settings in the DB, or in file.
     * Validates the setting and return them
     *
     * @return \Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto dto
     * @throws \App\Error\Exception\FormValidationException if the data does not validate
     */
    public static function getSettings(): MetadataTypesSettingsDto
    {
        if (!is_null(self::$settings)) {
            return self::$settings;
        }

        try {
            // fetch the settings from DB if any
            /** @var \App\Model\Table\OrganizationSettingsTable $orgSettingsTable */
            $orgSettingsTable = TableRegistry::getTableLocator()->get('OrganizationSettings');
            $setting = $orgSettingsTable->getFirstSettingOrFail(MetadataTypesSettingsGetService::ORG_SETTING_PROPERTY);

            // Deserialize and revalidate the settings
            if (!isset($setting->value) || !is_string($setting->value)) {
                throw new \Exception('Invalid setting type');
            }
            $data = json_decode($setting->value, true, 2, JSON_THROW_ON_ERROR);

            self::$settings = (new MetadataTypesSettingsAssertService())->assert($data);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            self::$settings = self::getDefaultSettings();
        }

        return self::$settings;
    }

    /**
     * Clears cached settings.
     *
     * @return void
     */
    public static function clear(): void
    {
        self::$settings = null;
    }
}
