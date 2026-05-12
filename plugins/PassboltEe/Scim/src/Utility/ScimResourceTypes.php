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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Utility;

use Passbolt\Scim\Exception\ScimException;
use Passbolt\Scim\Utility\ResourceType\GroupScimResourceType;
use Passbolt\Scim\Utility\ResourceType\UserScimResourceType;

/**
 * ResourceTypes class
 */
class ScimResourceTypes
{
    public const TYPE_USER = 'User';
    public const TYPE_GROUP = 'Group';

    /**
     * Type map
     *
     * @var array
     */
    public const MAPPING = [
        self::TYPE_USER => UserScimResourceType::class,
        self::TYPE_GROUP => GroupScimResourceType::class,
    ];

    /**
     * @param string $type
     * @return bool
     */
    public static function isValid(string $type): bool
    {
        return array_key_exists($type, self::MAPPING);
    }

    /**
     * Return the list of ResourceType objects
     *
     * @return array<\Passbolt\Scim\Utility\ScimObjectInterface>
     * @throws \Passbolt\Scim\Exception\ScimException
     * @throws \Exception
     */
    public static function getAll(): array
    {
        $resourceTypes = [];
        foreach (self::MAPPING as $identifier => $class) {
            $resourceTypes[] = self::build($identifier);
        }

        return $resourceTypes;
    }

    /**
     * Build a ResourceType given the name
     *
     * @param string $name
     * @return \Passbolt\Scim\Utility\ScimObjectInterface
     * @throws \Passbolt\Scim\Exception\ScimException
     * @throws \Exception
     */
    public static function build(string $name): ScimObjectInterface
    {
        $class = self::MAPPING[$name] ?? null;
        if (!$class) {
            throw new ScimException(__('Invalid ResourceType'));
        }

        return new $class();
    }
}
