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
 * @since         2.2.0
 */
namespace App\Utility;

use App\Model\Entity\Role;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;

/**
 * Class UserAccessControl
 *
 * Immutable object that allows taking a snapshot of the current
 * user id and role for a given action.
 *
 * @package App\Utility
 */
class UserAccessControl
{
    private $userId;
    private $roleName;

    /**
     * UserAccessControl constructor.
     * @param string $roleName The role name
     * @param string $userId the user uuid
     */
    public function __construct($roleName, $userId = null)
    {
        if (!is_string($roleName)) {
            throw new InternalErrorException('Invalid UserControl role name.');
        }
        if (isset($userId) && !Validation::uuid($userId)) {
            throw new InternalErrorException('Invalid UserControl user id.');
        }
        $this->userId = $userId;
        $this->roleName = $roleName;
    }

    /**
     * Get the user id
     * @return string
     */
    public function userId()
    {
        return $this->userId;
    }

    /**
     * Get the user role name
     * @return string
     */
    public function roleName()
    {
        return $this->roleName;
    }

    /**
     * Check if the user is an admin
     * @return bool
     */
    public function isAdmin()
    {
        return ($this->roleName() === Role::ADMIN);
    }

    /**
     * Convert the UserAccessControl data in array
     * @return array
     */
    public function toArray()
    {
        return [
            'userId' => $this->userId,
            'rolename' => $this->roleName
        ];
    }
}
