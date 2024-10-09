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
use Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto;

class MetadataKeysSettingsGetService
{
    use LocatorAwareTrait;

    public const ORG_SETTING_PROPERTY = 'metadataKeys';

    /**
     * TODO for v5 it should default to v5
     *
     * @return \Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto default settings when there is none set
     */
    public function getDefaultSettings(): MetadataKeysSettingsDto
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
    public function getSettings(): MetadataKeysSettingsDto
    {
        try {
            // fetch the settings from DB if any
            /** @var \App\Model\Table\OrganizationSettingsTable $orgSettingsTable */
            $orgSettingsTable = $this->fetchTable('OrganizationSettings');
            $setting = $orgSettingsTable->getFirstSettingOrFail(MetadataKeysSettingsGetService::ORG_SETTING_PROPERTY);

            // Deserialize and revalidate the settings
            if (!isset($setting->value) || !is_string($setting->value)) {
                throw new \Exception('Invalid setting type');
            }
            $data = json_decode($setting->value, true, 2, JSON_THROW_ON_ERROR);

            return (new MetadataKeysSettingsAssertService())->assert($data);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return $this->getDefaultSettings();
        }
    }
}
