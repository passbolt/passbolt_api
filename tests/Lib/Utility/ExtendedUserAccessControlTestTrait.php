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
 * @since         3.10.0
 */
namespace App\Test\Lib\Utility;

use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Utility\ExtendedUserAccessControl;
use App\Utility\UuidFactory;

/**
 * @see \App\Utility\ExtendedUserAccessControl
 */
trait ExtendedUserAccessControlTestTrait
{
    /**
     * Returns an extended user access control object with user role.
     * If you want more control over data(i.e. build from `User` entity) use `self::makeExtendedUac()` method.
     *
     * @return ExtendedUserAccessControl
     */
    public function mockExtendedUserAccessControl(): ExtendedUserAccessControl
    {
        return new ExtendedUserAccessControl(
            Role::USER,
            UuidFactory::uuid(),
            'user@passbolt.test',
            '127.0.0.1',
            'PHPUnit'
        );
    }

    /**
     * Returns an extended user access control object with admin role.
     * No need to provide any details as this will create a user(via Factory) and set default IP & user agent.
     *
     * If you want more control over data use `self::makeExtendedUac()` method.
     *
     * @return ExtendedUserAccessControl
     */
    public function mockExtendedAdminAccessControl(): ExtendedUserAccessControl
    {
        return new ExtendedUserAccessControl(
            Role::ADMIN,
            UuidFactory::uuid(),
            'user@passbolt.test',
            '127.0.0.1',
            'PHPUnit'
        );
    }

    /**
     * Build an `ExtendedUserAccessControl` object from given user entity.
     *
     * @param User $user User entity object.
     * @param string $userIp User IP address.
     * @param string $userAgent User agent.
     * @return ExtendedUserAccessControl
     */
    public function makeExtendedUac(User $user, string $userIp, string $userAgent): ExtendedUserAccessControl
    {
        return new ExtendedUserAccessControl($user->role->name, $user->id, $user->username, $userIp, $userAgent);
    }
}
