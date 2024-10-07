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
namespace Passbolt\Metadata\Test\Factory;

use App\Model\Entity\OrganizationSetting;
use App\Test\Factory\OrganizationSettingFactory;
use App\Utility\UuidFactory;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;
use Passbolt\Metadata\Service\MetadataTypesSettingsGetService;

/**
 * MetadataTypesSettingsFactory
 */
class MetadataTypesSettingsFactory extends OrganizationSettingFactory
{
    /**
     * @inheritDoc
     */
    protected function setDefaultTemplate(): void
    {
        parent::setDefaultTemplate();

        $this->patchData([
            'property' => MetadataTypesSettingsGetService::ORG_SETTING_PROPERTY,
            'property_id' => UuidFactory::uuid(OrganizationSetting::UUID_NAMESPACE . MetadataTypesSettingsGetService::ORG_SETTING_PROPERTY),
            'value' => json_encode(self::getDefaultDataV4()),
        ]);
    }

    public static function getDefaultDataV4(): array
    {
        return MetadataTypesSettingsGetService::defaultV4Settings();
    }

    public static function getDefaultDataV5(): array
    {
        return [
            MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES => MetadataTypesSettingsDto::V5,
            MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE => MetadataTypesSettingsDto::V5,
            MetadataTypesSettingsDto::DEFAULT_TAG_TYPE => MetadataTypesSettingsDto::V5,
            MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE => MetadataTypesSettingsDto::V5,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS => true,
        ];
    }

    public static function getDefaultDataV6(): array
    {
        return [
            MetadataTypesSettingsDto::DEFAULT_RESOURCE_TYPES => MetadataTypesSettingsDto::V5,
            MetadataTypesSettingsDto::DEFAULT_FOLDER_TYPE => MetadataTypesSettingsDto::V5,
            MetadataTypesSettingsDto::DEFAULT_TAG_TYPE => MetadataTypesSettingsDto::V5,
            MetadataTypesSettingsDto::DEFAULT_COMMENT_TYPE => MetadataTypesSettingsDto::V5,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_TAGS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS => true,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_TAGS => false,
            MetadataTypesSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS => false,
        ];
    }

    public function v4(): self
    {
        return $this->value(json_encode(self::getDefaultDataV4()));
    }

    public function v5(): self
    {
        return $this->value(json_encode(self::getDefaultDataV5()));
    }

    public function v6(): self
    {
        return $this->value(json_encode(self::getDefaultDataV6()));
    }
}
