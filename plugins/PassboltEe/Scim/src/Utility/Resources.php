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

namespace Passbolt\Scim\Utility;

use Exception;
use Passbolt\Scim\Exception\ScimException;
use Passbolt\Scim\Utility\Resource\UserResource;

/**
 * Resources class
 */
class Resources
{
    public const USERS = 'Users';
    public const GROUPS = 'Groups';

    /**
     * Type map
     *
     * @var array
     */
    public const MAPPING = [
        self::USERS => UserResource::class,
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
     * Build a Resource given the type
     *
     * @param string $type
     * @return \Passbolt\Scim\Utility\ResourceInterface
     * @throws \Exception
     */
    public static function build(string $type): ResourceInterface
    {
        $class = self::MAPPING[$type] ?? null;
        if (!$class) {
            throw new ScimException(sprintf('Invalid Resource type `%s`', $type));
        }

        $resource = new $class();
        if (!$resource instanceof ResourceInterface) {
            throw new Exception(sprintf('Invalid mapped class for Resource `%s`', $type));
        }

        return $resource;
    }
}
