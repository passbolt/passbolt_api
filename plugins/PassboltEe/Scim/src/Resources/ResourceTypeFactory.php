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
 * @since         4.1.0
 */

namespace Passbolt\Scim\Resources;

use Passbolt\Scim\Resources\ResourceType\GroupResourceType;
use Passbolt\Scim\Resources\ResourceType\UserResourceType;

/**
 * ResourceTypeFactory class
 */
class ResourceTypeFactory
{
    /**
     * Resource types
     */
    public const TYPE_USERS = 'Users';
    public const TYPE_GROUPS = 'Groups';

    /**
     * Resource type class map
     *
     * @var array
     */
    private static array $resourceTypeMap = [
        self::TYPE_USERS => UserResourceType::class,
        self::TYPE_GROUPS => GroupResourceType::class,
    ];

    /**
     * Return the resource type mapped class
     *
     * @param string $resourceType Resource Type (User, Group)
     * @return string
     * @throws \Exception
     */
    private static function getMappedClass(string $resourceType): string
    {
        if (!isset(self::$resourceTypeMap[$resourceType])) {
            throw new \Exception(sprintf('No mapped class found for ResourceType `%s`', $resourceType));
        }

        return self::$resourceTypeMap[$resourceType];
    }

    /**
     * Build a ResourceType object with the data from the request
     *
     * @param string $resourceType Resource Type (User, Group)
     * @return \Passbolt\Scim\Resources\ResourceTypeInterface
     * @throws \Exception
     */
    public static function build(string $resourceType): ResourceTypeInterface
    {
        $class = self::getMappedClass($resourceType);

        $object = new $class();
        if (!$object instanceof ResourceTypeInterface) {
            throw new \Exception('The mapped class must implement `\Passbolt\Scim\Resources\ResourceTypeInterface`');
        }

        return $object;
    }
}
