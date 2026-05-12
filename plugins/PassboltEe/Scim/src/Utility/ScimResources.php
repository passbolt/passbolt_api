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

use Passbolt\Scim\Exception\BadRequestException;
use Passbolt\Scim\Utility\Resource\UserScimResource;

/**
 * Resources class
 */
class ScimResources
{
    public const USERS = 'Users';
    public const GROUPS = 'Groups';

    /**
     * Type map
     *
     * @var array
     */
    public const MAPPING = [
        self::USERS => UserScimResource::class,
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
     * @return \Passbolt\Scim\Utility\ScimResourceInterface
     * @throws \Exception
     */
    public static function build(string $type): ScimResourceInterface
    {
        $class = self::MAPPING[$type] ?? null;
        if (!$class) {
            throw new BadRequestException(sprintf('Invalid Resource type `%s`', $type), 400);
        }

        return new $class();
    }
}
