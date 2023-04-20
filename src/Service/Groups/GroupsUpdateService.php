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

namespace App\Service\Groups;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Group;
use App\Model\Entity\GroupsUser;
use App\Model\Validation\GroupsUsersChange\GroupsUsersChangeValidator;
use App\Service\GroupsUsers\GroupsUsersAddService;
use App\Service\GroupsUsers\GroupsUsersDeleteService;
use App\Service\GroupsUsers\GroupsUsersUpdateService;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsUpdateService
{
    public const UPDATE_SUCCESS_EVENT_NAME = 'GroupsUpdateController.update.success';

    /**
     * @var \App\Model\Table\GroupsTable
     */
    private $groupsTable;

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var \App\Service\Groups\GroupGetService
     */
    private $groupGetService;

    /**
     * @var \App\Service\GroupsUsers\GroupsUsersAddService
     */
    private $groupsUsersCreateService;

    /**
     * @var \App\Service\GroupsUsers\GroupsUsersUpdateService
     */
    private $groupsUsersUpdateService;

    /**
     * @var \App\Service\GroupsUsers\GroupsUsersDeleteService
     */
    private $groupsUsersRemoveService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->groupsTable = TableRegistry::getTableLocator()->get('Groups');
        /** @phpstan-ignore-next-line */
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->groupGetService = new GroupGetService();
        $this->groupsUsersCreateService = new GroupsUsersAddService();
        $this->groupsUsersUpdateService = new GroupsUsersUpdateService();
        $this->groupsUsersRemoveService = new GroupsUsersDeleteService();
    }

    /**
     * Update a group.
     *
     * @note if the operator is not a group manager, requested additions will be ignored.
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $groupId The identified of the group to update
     * @param array $metaData The group meta data to update
     * @param array|null $changes The list of group users changes to apply
     * [
     *   [
     *     string $id The group user identifier (Required for delete an update scenarios)
     *     string $user_id The user identifier (Required for add scenarios)
     *     string $is_admin Is the user group manager (Required for add and update scenarios)
     *     bool $delete Should delete the group user (Required for delete scenarios)
     *   ],
     *   ...
     * ]
     * @param array|null $secrets The list of new secrets to add
     * [
     *   [
     *     string $user_id The user identifier
     *     string $resource_id The resource identifier
     *     string $data The encrypted secret
     *   ],
     *   ...
     * ]
     * @return \App\Model\Entity\Group
     * @throws \Exception If an unexpected error occurred.
     */
    public function update(
        UserAccessControl $uac,
        string $groupId,
        array $metaData,
        ?array $changes = [],
        ?array $secrets = []
    ): Group {
        $group = $this->groupGetService->getNotDeletedOrFail($groupId);
        $this->assertChanges($group, $changes);

        $this->groupsTable->getConnection()->transactional(
            function () use ($uac, $group, $metaData, $changes, $secrets) {
                $this->updateMetaData($uac, $group, $metaData);
                $addedGroupsUsers = $this->addGroupsUsers($uac, $group, $changes, $secrets);
                $updatedGroupsUsers = $this->updateGroupsUsers($uac, $group, $changes);
                $deletedGroupsUsers = $this->deleteGroupsUsers($uac, $group, $changes);
                $this->notifyUsers($uac, $group, $addedGroupsUsers, $updatedGroupsUsers, $deletedGroupsUsers);
            }
        );

        return $group;
    }

    /**
     * Assert the list of changes.
     *
     * @param \App\Model\Entity\Group $group The group to update
     * @param array $changes The list of group users changes to apply
     * @return void
     */
    private function assertChanges(Group $group, array $changes): void
    {
        $validator = new GroupsUsersChangeValidator();
        foreach ($changes as $rowIndexRef => $change) {
            $errors = $validator->validate($change);
            if (!empty($errors)) {
                $group->setError('groups_users', [$rowIndexRef => $errors]);
                $this->handleValidationErrors($group);
            }
        }
    }

    /**
     * Update the group meta data.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \App\Model\Entity\Group $group The target group
     * @param array $metaData The raw meta data to update
     * @return void
     */
    private function updateMetaData(UserAccessControl $uac, Group $group, array $metaData): void
    {
        if (!$uac->isAdmin() || empty($metaData)) {
            return;
        }
        $groupPatchOptions = [
            'accessibleFields' => [
                'name' => true,
                'modified_by' => true,
            ],
        ];
        $metaData['modified_by'] = $uac->getId();
        $this->groupsTable->patchEntity($group, $metaData, $groupPatchOptions);
        $this->groupsTable->save($group);
        if ($group->hasErrors()) {
            $this->handleValidationErrors($group);
        }
    }

    /**
     * Handle group validation errors.
     *
     * @param \App\Model\Entity\Group $group The target group
     * @throws \App\Error\Exception\ValidationException If the group has errors.
     * @return void
     */
    private function handleValidationErrors(Group $group): void
    {
        $msg = __('Could not validate group data.');
        throw new ValidationException($msg, $group, $this->groupsTable);
    }

    /**
     * Add groups users.
     *
     * @note if the operator is not a group manager, requested additions will be ignored.
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $changes The list of group users changes.
     * @param array $secretsData The list of secrets to add.
     * @return array Array of added GroupsUser
     * @throws \Exception If an unexpected error occurred.
     */
    private function addGroupsUsers(UserAccessControl $uac, Group $group, array $changes, array $secretsData): array
    {
        $addedGroupsUsers = [];
        $isUacManager = $this->groupsUsersTable->isManager($uac->getId(), $group->id);

        if (!$isUacManager) {
            return $addedGroupsUsers;
        }

        foreach ($changes as $rowIndexRef => $groupUserData) {
            if (GroupsUsersChangeValidator::isAddChange($groupUserData)) {
                $addedGroupsUsers[] = $this->addGroupUser($uac, $group, $groupUserData, $secretsData, $rowIndexRef);
            }
        }

        return $addedGroupsUsers;
    }

    /**
     * Add a group user.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $groupUserData The data of the group user to add.
     * @param array $secretsData The data of the secrets to add.
     * @param int $rowIndexRef The index of the treated group user in the request data, for error purpose.
     * @return \App\Model\Entity\GroupsUser
     * @throws \App\Error\Exception\ValidationException If a validation error occured while inserting the new group user.
     * @throws \Exception If an unexpected error occurred.
     */
    private function addGroupUser(
        UserAccessControl $uac,
        Group $group,
        array $groupUserData,
        array $secretsData,
        int $rowIndexRef
    ): GroupsUser {
        $userId = Hash::get($groupUserData, 'user_id');
        $groupUserData['group_id'] = $group->id;
        $userSecretsData = Hash::extract($secretsData, "{n}[user_id={$userId}]");

        try {
            $groupUser = $this->groupsUsersCreateService->add($uac, $groupUserData, $userSecretsData);
        } catch (ValidationException $e) {
            $group->setError('groups_users', [$rowIndexRef => $e->getErrors()]);
            $this->handleValidationErrors($group);
        }

        /** @phpstan-ignore-next-line */
        return $groupUser;
    }

    /**
     * Update groups users.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $changes The list of group users changes.
     * @return array Array of updated GroupUser
     */
    private function updateGroupsUsers(UserAccessControl $uac, Group $group, array $changes): array
    {
        $updatedGroupsUsers = [];
        $updateGroupsUsersChanges = [];

        // Extract groups users update changes.
        foreach ($changes as $rowIndexRef => $change) {
            if (GroupsUsersChangeValidator::isUpdateChange($change)) {
                // Preserve the changes index for later associated error treatment.
                $updateGroupsUsersChanges[$rowIndexRef] = $change;
            }
        }

        /*
         * Sort the changes to execute "add manager" update operations first. Each update operation of the group will
         * check if there is a group manager, therfore sort the changes in order to get changes that grant the manager role
         * on top of the list.
         * Preserve the changes index for later associated error treatment.
         */
        $this->sortGroupUsersUpdateChanges($updateGroupsUsersChanges);

        foreach ($updateGroupsUsersChanges as $rowIndexRef => $updateGroupsUsersChange) {
            $updatedGroupsUsers[] = $this->updateGroupUser($uac, $group, $updateGroupsUsersChange, $rowIndexRef);
        }

        return $updatedGroupsUsers;
    }

    /**
     * Sort the list of groups users update change to get on top groups users change that grant the manager role.
     *
     * @param array $updateGroupsUsersChanges The groups users update changes.
     * @return void
     */
    private function sortGroupUsersUpdateChanges(array &$updateGroupsUsersChanges): void
    {
        uasort($updateGroupsUsersChanges, function (array $groupUserChangeA, array $groupUserChangeB) {
            $changeAIsAdmin = Hash::get($groupUserChangeA, 'is_admin');
            $changeBIsAdmin = Hash::get($groupUserChangeB, 'is_admin');
            if ($changeAIsAdmin) {
                return -1;
            } elseif ($changeBIsAdmin) {
                return 1;
            }

            return 0;
        });
    }

    /**
     * Update a group user.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $groupUserData The data of the group user to update.
     * @param int $rowIndexRef The index of the treated group user in the request data, for error purpose.
     * @return \App\Model\Entity\GroupsUser
     */
    private function updateGroupUser(
        UserAccessControl $uac,
        Group $group,
        array $groupUserData,
        int $rowIndexRef
    ): GroupsUser {
        $groupUser = $this->getAndAssertGroupUserFromData($group, $groupUserData, $rowIndexRef);
        try {
            $groupUser = $this->groupsUsersUpdateService->update($uac, $groupUser->id, $groupUserData);
        } catch (ValidationException $e) {
            $group->setError('groups_users', [$rowIndexRef => $e->getErrors() ?? $e->getMessage()]);
            $this->handleValidationErrors($group);
        } catch (RecordNotFoundException $e) {
            $group->setError('groups_users', [$rowIndexRef => ['id' => ['exists' => ['Cannot find the group user.']]]]);
            $this->handleValidationErrors($group);
        }

        return $groupUser;
    }

    /**
     * Get and assert group user from group user data
     *
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $groupUserData The data of the group user to update or delete.
     * @param int $rowIndexRef The index of the treated group user in the request data, for error purpose.
     * @return \App\Model\Entity\GroupsUser
     */
    private function getAndAssertGroupUserFromData(Group $group, array $groupUserData, int $rowIndexRef): GroupsUser
    {
        $groupUserId = Hash::get($groupUserData, 'id');

        try {
            $groupUser = $this->groupsUsersTable->get($groupUserId);
        } catch (RecordNotFoundException $e) {
            $group->setError('groups_users', [$rowIndexRef => ['id' => ['exists' => 'Cannot find the group user.']]]);
            $this->handleValidationErrors($group);
        }

        /** @phpstan-ignore-next-line */
        if ($groupUser->group_id !== $group->id) {
            $group->setError('groups_users', [$rowIndexRef => ['id' => ['exists' => 'Cannot find the group user.']]]);
            $this->handleValidationErrors($group);
        }

        /** @phpstan-ignore-next-line */
        return $groupUser;
    }

    /**
     * Delete groups users.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $changes The list of group users changes.
     * @return array Array of deleted GroupUser
     */
    private function deleteGroupsUsers(UserAccessControl $uac, Group $group, array $changes): array
    {
        $deletedGroupsUsers = [];

        foreach ($changes as $rowIndexRef => $change) {
            if (GroupsUsersChangeValidator::isDeleteChange($change)) {
                $deletedGroupsUsers[] = $this->deleteGroupUser($uac, $group, $change, $rowIndexRef);
            }
        }

        return $deletedGroupsUsers;
    }

    /**
     * Delete a group user
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $groupUserData The data of the group user to delete.
     * @param int $rowIndexRef The index of the treated group user in the request data, for error purpose.
     * @return \App\Model\Entity\GroupsUser
     * @throws \Exception
     */
    private function deleteGroupUser(
        UserAccessControl $uac,
        Group $group,
        array $groupUserData,
        int $rowIndexRef
    ): GroupsUser {
        $groupUser = $this->getAndAssertGroupUserFromData($group, $groupUserData, $rowIndexRef);

        try {
            $this->groupsUsersRemoveService->delete($uac, $groupUser->id);
        } catch (ValidationException $e) {
            $group->setError('groups_users', [$rowIndexRef => $e->getErrors() ?? $e->getMessage()]);
            $this->handleValidationErrors($group);
        } catch (RecordNotFoundException $e) {
            $group->setError('groups_users', [$rowIndexRef => ['id' => ['exists' => ['Cannot find the group user.']]]]);
            $this->handleValidationErrors($group);
        }

        return $groupUser;
    }

    /**
     * Notify the users that they group have been deleted
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param \App\Model\Entity\Group $group The updated group
     * @param array $addedGroupsUsers the list of added groups users
     * @param array $updatedGroupsUsers the list of updated groups suers
     * @param array $deletedGroupsUsers the list of deleted groups users
     * @return void
     */
    private function notifyUsers(
        UserAccessControl $uac,
        Group $group,
        array $addedGroupsUsers,
        array $updatedGroupsUsers,
        array $deletedGroupsUsers
    ): void {
        $event = new Event(static::UPDATE_SUCCESS_EVENT_NAME, $this, [
            'group' => $group,
            'addedGroupsUsers' => $addedGroupsUsers,
            'updatedGroupsUsers' => $updatedGroupsUsers,
            'removedGroupsUsers' => $deletedGroupsUsers,
            'userId' => $uac->getId(),
        ]);
        $this->groupsTable->getEventManager()->dispatch($event);
    }
}
