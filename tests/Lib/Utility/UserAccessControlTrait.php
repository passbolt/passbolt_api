<?php
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
 * @since         2.0.0
 */
namespace App\Test\Lib\Utility;

use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;

trait UserAccessControlTrait
{
    /**
     * Asserts that an object has specified attributes.
     *
     * @param string $user ada, betty, etc.
     * @param string $role optional
     * @return UserAccessControl
     */
    public function mockUserAccessControl($user, $role = Role::GUEST)
    {
        return new UserAccessControl($role, UuidFactory::uuid('user.id.' . $user), $user . '@passbolt.com');
    }
}
