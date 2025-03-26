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
use Cake\ORM\TableRegistry;
use Exception;
use Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto;

class MetadataKeysSettingsGetService
{
    public const ORG_SETTING_PROPERTY = 'metadataKeys';

    /**
     * @var \Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto|null
     */
    private static ?MetadataKeysSettingsDto $settings = null;

    /**
     * TODO for v5 it should default to v5
     *
     * @return \Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto default settings when there is none set
     */
    public static function getDefaultSettings(): MetadataKeysSettingsDto
    {
        return new MetadataKeysSettingsDto(self::getDefaultSettingsArray());
    }

    /**
     * @return array default settings values
     */
    public static function getDefaultSettingsArray(): array
    {
        return [
            MetadataKeysSettingsDto::ALLOW_USAGE_OF_PERSONAL_KEYS => true,
            MetadataKeysSettingsDto::ZERO_KNOWLEDGE_KEY_SHARE => false,
        ];
    }

    /**
     * Read the metadata settings in the DB, or in file.
     * Validates the setting and return them
     *
     * @return \Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto dto
     * @throws \App\Error\Exception\FormValidationException if the data does not validate
     */
    public static function getSettings(): MetadataKeysSettingsDto
    {
        if (!is_null(self::$settings)) {
            return self::$settings;
        }

        try {
            // fetch the settings from DB if any
            /** @var \App\Model\Table\OrganizationSettingsTable $orgSettingsTable */
            $orgSettingsTable = TableRegistry::getTableLocator()->get('OrganizationSettings');
            $setting = $orgSettingsTable->getFirstSettingOrFail(MetadataKeysSettingsGetService::ORG_SETTING_PROPERTY);

            // Deserialize and revalidate the settings
            if (!isset($setting->value) || !is_string($setting->value)) {
                throw new Exception('Invalid setting type');
            }
            $data = json_decode($setting->value, true, 2, JSON_THROW_ON_ERROR);

            self::$settings = (new MetadataKeysSettingsAssertService())->assert($data);
        } catch (Exception $exception) {
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
