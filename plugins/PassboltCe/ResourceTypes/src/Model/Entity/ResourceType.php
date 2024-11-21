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
 * @since         3.0.0
 */
namespace Passbolt\ResourceTypes\Model\Entity;

use App\Utility\UuidFactory;
use Cake\ORM\Entity;

/**
 * ResourceType Entity
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string|null $definition
 * @property string|null $description
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 * @property \App\Model\Entity\Resource[] $resources
 */
class ResourceType extends Entity
{
    /**
     * Slugs.
     */
    public const SLUG_PASSWORD_STRING = 'password-string';
    public const SLUG_PASSWORD_AND_DESCRIPTION = 'password-and-description';
    // TODO: Move this to TotpResourceType entity
    public const SLUG_STANDALONE_TOTP = 'totp';
    public const SLUG_PASSWORD_DESCRIPTION_TOTP = 'password-description-totp';

    // v5 slugs
    public const SLUG_V5_PASSWORD_STRING = 'v5-password-string';
    public const SLUG_V5_DEFAULT = 'v5-default';
    public const SLUG_V5_TOTP_STANDALONE = 'v5-totp-standalone';
    public const SLUG_V5_DEFAULT_WITH_TOTP = 'v5-default-with-totp';
    public const V5_RESOURCE_TYPE_SLUGS = [
        self::SLUG_V5_PASSWORD_STRING,
        self::SLUG_V5_DEFAULT,
        self::SLUG_V5_TOTP_STANDALONE,
        self::SLUG_V5_DEFAULT_WITH_TOTP,
    ];

    protected $_accessible = [
        'name' => false,
        'slug' => false,
        'description' => false,
        'definition' => false,
        'deleted' => false,
    ];

    /**
     * @return bool true if the resource type is soft deleted
     */
    public function isDeleted(): bool
    {
        return isset($this->deleted);
    }

    /**
     * Returns V4-V5 resource type ID mapping.
     *
     * @return array
     */
    public static function getV5Mapping(): array
    {
        return [
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_PASSWORD_STRING) =>
                UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_PASSWORD_STRING),
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_PASSWORD_AND_DESCRIPTION) =>
                UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_DEFAULT),
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_STANDALONE_TOTP) =>
                UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_TOTP_STANDALONE),
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_PASSWORD_DESCRIPTION_TOTP) =>
                UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_DEFAULT_WITH_TOTP),
        ];
    }

    /**
     * Returns V4 resource types IDs array.
     *
     * @return array
     * @throws \Exception
     */
    public static function getV4ResourceTypes(): array
    {
        return [
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_PASSWORD_STRING),
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_PASSWORD_AND_DESCRIPTION),
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_STANDALONE_TOTP),
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_PASSWORD_DESCRIPTION_TOTP),
        ];
    }

    /**
     * Returns V5 resource types IDs array.
     *
     * @return array
     * @throws \Exception
     */
    public static function getV5ResourceTypes(): array
    {
        return [
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_PASSWORD_STRING),
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_DEFAULT),
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_TOTP_STANDALONE),
            UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_DEFAULT_WITH_TOTP),
        ];
    }
}
