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
 * @since         2.13.0
 */

namespace App\Service\GroupsUsers;

use App\Error\Exception\ValidationException;
use App\Model\Entity\GroupsUser;
use App\Model\Table\GroupsUsersTable;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;

class GroupsUsersCreateService
{
    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * GroupsUsersCreateService constructor.
     *
     * @param \App\Model\Table\GroupsUsersTable|null $groupsUsersTable table
     */
    public function __construct(?GroupsUsersTable $groupsUsersTable = null)
    {
        $this->groupsUsersTable = $groupsUsersTable ?? TableRegistry::getTableLocator()->get('GroupsUsers');
    }

    /**
     * Create a group user.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param array|null $data The group user data
     * [
     *   string $group_id The group
     *   string $user_id The user
     *   string $is_admin Is the user group manager
     * ]
     * @return \App\Model\Entity\GroupsUser
     * @throws \Exception
     */
    public function create(UserAccessControl $uac, ?array $data = []): GroupsUser
    {
        $groupUser = null;
        $this->groupsUsersTable->getConnection()->transactional(function () use (&$groupUser, $uac, $data) {
            $groupUser = $this->createGroupUser($uac, $data);
        });

        return $groupUser;
    }

    /**
     * Create and save a group user.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param array|null $data The group user data
     * [
     *   string $group_id The group
     *   string $user_id The user
     *   string $is_admin Is the user group manager
     * ]
     * @return \App\Model\Entity\GroupsUser
     */
    private function createGroupUser(UserAccessControl $uac, ?array $data = []): GroupsUser
    {
        $groupUser = $this->buildGroupUserEntity($uac, $data);
        $this->handlePermissionValidationErrors($groupUser);
        $this->groupsUsersTable->save($groupUser);
        $this->handlePermissionValidationErrors($groupUser);

        return $groupUser;
    }

    /**
     * Build the group user entity.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param array|null $data The group user data
     * [
     *   string $group_id The group
     *   string $user_id The user
     *   string $is_admin Is the user group manager
     * ]
     * @return \App\Model\Entity\GroupsUser
     */
    private function buildGroupUserEntity(UserAccessControl $uac, ?array $data = []): GroupsUser
    {
        $operatorId = $uac->getId();
        $data = array_merge([
            'created_by' => $operatorId,
            'modified_by' => $operatorId,
        ], $data);
        $accessibleFields = [
            'group_id' => true,
            'user_id' => true,
            'is_admin' => true,
            'created_by' => true,
            'modified_by' => true,
        ];

        return $this->groupsUsersTable->newEntity($data, ['accessibleFields' => $accessibleFields]);
    }

    /**
     * Handle group user validation errors.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The group user
     * @return void
     */
    private function handlePermissionValidationErrors(GroupsUser $groupUser): void
    {
        $errors = $groupUser->getErrors();
        if (!empty($errors)) {
            $msg = __('Could not validate the group user data.');
            throw new ValidationException($msg, $groupUser, $this->groupsUsersTable);
        }
    }
}
