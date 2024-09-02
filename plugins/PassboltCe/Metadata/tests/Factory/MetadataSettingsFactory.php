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
use Passbolt\Metadata\Model\Dto\MetadataSettingsDto;
use Passbolt\Metadata\Service\MetadataSettingsGetService;

/**
 * MetadataSettingsFactory
 */
class MetadataSettingsFactory extends OrganizationSettingFactory
{
    /**
     * @inheritDoc
     */
    protected function setDefaultTemplate(): void
    {
        parent::setDefaultTemplate();

        $this->patchData([
            'property' => MetadataSettingsGetService::ORG_SETTING_PROPERTY,
            'property_id' => UuidFactory::uuid(OrganizationSetting::UUID_NAMESPACE . MetadataSettingsGetService::ORG_SETTING_PROPERTY),
            'value' => json_encode(self::getDefaultDataV4()),
        ]);
    }

    public static function getDefaultDataV4(): array
    {
        return [
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
        ];
    }

    public static function getDefaultDataV5(): array
    {
        return [
            MetadataSettingsDto::DEFAULT_RESOURCE_TYPES => MetadataSettingsDto::V5,
            MetadataSettingsDto::DEFAULT_FOLDER_TYPE => MetadataSettingsDto::V5,
            MetadataSettingsDto::DEFAULT_TAG_TYPE => MetadataSettingsDto::V5,
            MetadataSettingsDto::DEFAULT_COMMENT_TYPE => MetadataSettingsDto::V5,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_TAGS => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_TAGS => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS => true,
        ];
    }

    public static function getDefaultDataV6(): array
    {
        return [
            MetadataSettingsDto::DEFAULT_RESOURCE_TYPES => MetadataSettingsDto::V5,
            MetadataSettingsDto::DEFAULT_FOLDER_TYPE => MetadataSettingsDto::V5,
            MetadataSettingsDto::DEFAULT_TAG_TYPE => MetadataSettingsDto::V5,
            MetadataSettingsDto::DEFAULT_COMMENT_TYPE => MetadataSettingsDto::V5,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_RESOURCES => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_FOLDERS => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_TAGS => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V5_COMMENTS => true,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_RESOURCES => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_FOLDERS => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_TAGS => false,
            MetadataSettingsDto::ALLOW_CREATION_OF_V4_COMMENTS => false,
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
