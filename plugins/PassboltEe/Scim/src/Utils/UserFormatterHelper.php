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

namespace Passbolt\Scim\Utils;

use Passbolt\Scim\Resources\ResourceType\UserResourceType;

class UserFormatterHelper
{
    /**
     * Invert the active value since it is mapped in database to the field `deleted`
     *
     * @param mixed $value Value to format
     * @param \Passbolt\Scim\Resources\ResourceType\UserResourceType $user Resource
     * @return bool
     */
    public function active($value, UserResourceType $user): bool
    {
        return !filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
