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

/**
 * ScimConstants class
 */
class ScimConstants
{
    public const CONTENT_TYPE = 'application/scim+json';

    public const ATTRIBUTE_MUTABILITY_READ_ONLY = 'readOnly';
    public const ATTRIBUTE_MUTABILITY_READ_WRITE = 'readWrite';
    public const ATTRIBUTE_MUTABILITY_IMMUTABLE = 'immutable';

    /**
     * @param string $mutability
     * @return bool
     */
    public static function isValidAttributeMutability(string $mutability): bool
    {
        return in_array($mutability, [
            self::ATTRIBUTE_MUTABILITY_READ_ONLY,
            self::ATTRIBUTE_MUTABILITY_READ_WRITE,
            self::ATTRIBUTE_MUTABILITY_IMMUTABLE,
        ]);
    }
}
