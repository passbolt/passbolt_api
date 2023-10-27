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
 * @since         3.7.0
 */

namespace App\Service\GroupsUsers;

use App\Error\Exception\ValidationException;
use App\Model\Entity\GroupsUser;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsUsersUpdateService
{
    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * GroupsUsersUpdateService constructor.
     */
    public function __construct()
    {
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
    }

    /**
     * Update group user.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $groupUserId The identifier of the group user to update
     * @param array $data The data to use to update the group user
     * @return \App\Model\Entity\GroupsUser
     */
    public function update(UserAccessControl $uac, string $groupUserId, array $data = []): GroupsUser
    {
        $groupUser = $this->groupsUsersTable->get($groupUserId);
        $this->assertAtLeastOneGroupManager($groupUser, $data);
        $groupUser = $this->patchGroupUserEntity($uac, $groupUser, $data);
        $this->saveGroupUser($groupUser);

        return $groupUser;
    }

    /**
     * Assert that the group to remove the group user in will have at least one manager after removing the group user.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to check the group for
     * @param array $data The data to use to update the group user
     * @return void
     * @throws \App\Error\Exception\ValidationException Cannot delete the last group manager.
     */
    private function assertAtLeastOneGroupManager(GroupsUser $groupUser, array $data): void
    {
        $isAdmin = Hash::get($data, 'is_admin');
        if ($isAdmin || is_null($isAdmin)) {
            return;
        }
        if ($groupUser->is_admin === $isAdmin) {
            return;
        }

        $groupManagersCount = $this->groupsUsersTable->findByGroupIdAndIsAdmin($groupUser->group_id, true)
            ->count();

        if ($groupManagersCount === 1) {
            $groupUser->setError('is_admin', ['at_least_one_group_manager' => 'Cannot delete the last group manager.']);
            throw new ValidationException('Cannot update group user.', $groupUser);
        }
    }

    /**
     * Patch the group user with the data to update.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to update.
     * @param array $data The date to use to patch the group user
     * @return \App\Model\Entity\GroupsUser
     */
    private function patchGroupUserEntity(UserAccessControl $uac, GroupsUser $groupUser, array $data): GroupsUser
    {
        $patchEntityOptions = ['accessibleFields' => ['is_admin' => true, 'modified_by' => true]];
        $groupUser = $this->groupsUsersTable->patchEntity($groupUser, $data, $patchEntityOptions);
        if ($groupUser->hasErrors()) {
            $this->handleValidationErrors($groupUser);
        }
        if ($groupUser->isDirty()) {
            $groupUser->set('modified_by', $uac->getId());
        }

        return $groupUser;
    }

    /**
     * Save the group user.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to update.
     * @return void
     */
    private function saveGroupUser(GroupsUser $groupUser): void
    {
        $this->groupsUsersTable->save($groupUser);
        if ($groupUser->hasErrors()) {
            $this->handleValidationErrors($groupUser);
        }
    }

    /**
     * Handle groups users validation errors.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The list of errors
     * @throws \App\Error\Exception\ValidationException If the provided data does not validate.
     * @return void
     */
    private function handleValidationErrors(GroupsUser $groupUser): void
    {
        $msg = __('Could not validate group user data.');
        throw new ValidationException($msg, $groupUser, $this->groupsUsersTable);
    }
}
