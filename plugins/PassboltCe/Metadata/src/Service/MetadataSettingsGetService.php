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
use Passbolt\Metadata\Model\Dto\MetadataSettingsDto;

class MetadataSettingsGetService
{
    use LocatorAwareTrait;

    public const ORG_SETTING_PROPERTY = 'metadata';

    /**
     * TODO for v5 it should default to v5
     *
     * @return \Passbolt\Metadata\Model\Dto\MetadataSettingsDto default settings when there is none set
     */
    public function getDefaultSettings(): MetadataSettingsDto
    {
        return new MetadataSettingsDto([
            MetadataSettingsDto::DEFAULT_RESOURCE_TYPES => MetadataSettingsDto::V4,
            MetadataSettingsDto::DEFAULT_FOLDER_TYPE => MetadataSettingsDto::V4,
            MetadataSettingsDto::DEFAULT_TAG_TYPE => MetadataSettingsDto::V4,
            MetadataSettingsDto::DEFAULT_COMMENT_TYPE => MetadataSettingsDto::V4,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_TAGS => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_TAGS => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS => true,
        ]);
    }

    /**
     * Read the metadata settings in the DB, or in file.
     * Validates the setting and return them
     *
     * @return \Passbolt\Metadata\Model\Dto\MetadataSettingsDto dto
     * @throws \App\Error\Exception\FormValidationException if the data does not validate
     */
    public function getSettings(): MetadataSettingsDto
    {
        try {
            // fetch the settings from DB if any
            /** @var \App\Model\Table\OrganizationSettingsTable $orgSettingsTable */
            $orgSettingsTable = $this->fetchTable('OrganizationSettings');
            $setting = $orgSettingsTable->getFirstSettingOrFail(MetadataSettingsGetService::ORG_SETTING_PROPERTY);

            // Deserialize and revalidate the settings
            if (!isset($setting->value) || !is_string($setting->value)) {
                throw new \Exception('Invalid setting type');
            }
            $data = json_decode($setting->value, true, 2, JSON_THROW_ON_ERROR);

            return (new MetadataSettingsAssertService())->assert($data);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return $this->getDefaultSettings();
        }
    }
}
