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
 * @since         2.13.0
 */

namespace App\Service\Groups;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Group;
use App\Model\Entity\GroupsUser;
use App\Model\Table\GroupsTable;
use App\Model\Table\GroupsUsersTable;
use App\Model\Table\PermissionsTable;
use App\Model\Table\ResourcesTable;
use App\Service\Secrets\SecretsUpdateSecretsService;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsUpdateService
{
    const AFTER_GROUP_USER_ADDED_EVENT_NAME = 'Service.GroupsUpdate.afterGroupUserAdded';
    const AFTER_GROUP_USER_REMOVED_EVENT_NAME = 'Service.GroupsUpdate.afterGroupUserRemoved';
    const UPDATE_SUCCESS_EVENT_NAME = 'GroupsUpdateController.update.success';

    /**
     * @var GroupsTable
     */
    private $groupsTable;

    /**
     * @var GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var GroupsUpdateGroupUsersService
     */
    private $groupsUpdateGroupUsersService;

    /**
     * @var PermissionsTable
     */
    private $permissionsTable;

    /**
     * @var ResourcesTable
     */
    private $resourcesTable;

    /**
     * @var SecretsUpdateSecretsService
     */
    private $secretsUpdateSecretsService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->groupsTable = TableRegistry::getTableLocator()->get('Groups');
        $this->groupsUpdateGroupUsersService = new GroupsUpdateGroupUsersService();
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->resourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $this->secretsUpdateSecretsService = new SecretsUpdateSecretsService();
    }

    /**
     * Update a group.
     *
     * @param UserAccessControl $uac The current user
     * @param string $groupId The target group
     * @param array $metaData The group meta data to update
     * @param array $changes The list of group users changes to apply
     * @param array $secrets The list of new secrets to add
     * @return Group
     * @throws \Exception
     */
    public function update(UserAccessControl $uac, string $groupId, array $metaData, array $changes = [], array $secrets = [])
    {
        $group = $this->getGroup($groupId);

        $this->groupsTable->getConnection()->transactional(function () use ($uac, $group, $metaData, $changes, $secrets) {
            $resourcesIdsGroupHasAccess = $this->getResourcesIdsGroupHasAccess($group);
            $this->updateMetaData($uac, $group, $metaData);
            $updateGroupsUsersResult = $this->updateGroupUsers($uac, $group, $changes);
            $addedGroupUsers = Hash::get($updateGroupsUsersResult, 'added', []);
            $updatedGroupUsers = Hash::get($updateGroupsUsersResult, 'updated', []);
            $removedGroupUsers = Hash::get($updateGroupsUsersResult, 'removed', []);
            $this->updateResourcesSecrets($uac, $group, $resourcesIdsGroupHasAccess, $secrets, $addedGroupUsers, $removedGroupUsers);
            $this->postGroupUsersAdded($uac, $group, $addedGroupUsers);
            $this->postGroupUsersRemoved($uac, $group, $removedGroupUsers, $resourcesIdsGroupHasAccess);
            $this->notifyUsers($uac, $group, $addedGroupUsers, $updatedGroupUsers, $removedGroupUsers);
        });

        return $group;
    }

    /**
     * Retrieve the group.
     *
     * @param string $groupId The target group to retrieve
     * @return Group
     * @throws NotFoundException If the group does not exist.
     */
    private function getGroup(string $groupId)
    {
        $group = $this->groupsTable->findById($groupId)->first();
        if (empty($group) || $group->deleted) {
            throw new NotFoundException(__('The group does not exist.'));
        }

        return $group;
    }

    /**
     * Retrieve the resources ids a group has access.
     *
     * @param Group $group The target group
     * @return array The list of resources ids
     */
    private function getResourcesIdsGroupHasAccess(Group $group)
    {
        return $this->permissionsTable->findAllByAro(PermissionsTable::RESOURCE_ACO, $group->id)
            ->select('aco_foreign_key')
            ->extract('aco_foreign_key')
            ->toArray();
    }

    /**
     * Update the group meta data.
     *
     * @param UserAccessControl $uac The operator
     * @param Group $group The target group
     * @param array $metaData The raw meta data to update
     * @return void
     */
    private function updateMetaData(UserAccessControl $uac, Group $group, array $metaData)
    {
        // Only admin can update the group meta data.
        if (!$uac->isAdmin() || empty($metaData)) {
            return;
        }
        $groupPatchOptions = [
            'accessibleFields' => [
                'name' => true,
                'modified_by' => true,
            ],
        ];
        $metaData['modified_by'] = $uac->userId();
        $this->groupsTable->patchEntity($group, $metaData, $groupPatchOptions);
        $this->groupsTable->save($group);
        $this->handleValidationErrors($group);
    }

    /**
     * Handle group validation errors.
     *
     * @param Group $group The target group
     * @return void
     * @throws ValidationException If the provided data does not validate.
     */
    private function handleValidationErrors(Group $group)
    {
        $errors = $group->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate group data.'), $group, $this->groupsTable);
        }
    }

    /**
     * Update the group users of a group.
     *
     * @param UserAccessControl $uac The current user
     * @param Group $group The target group
     * @param array $changes The list of group users changes to apply
     * @return array
     * [
     *   added => <array> List of added group users
     *   deleted => <array> List of deleted group users
     *   updated => <array> List of updated group users
     * ]
     * @throws \Exception If something unexpected occurred
     */
    private function updateGroupUsers(UserAccessControl $uac, Group $group, array $changes)
    {
        if (empty($changes)) {
            return [];
        }

        // Only a group manager can add new members to a group.
        $canAdd = $this->groupsUsersTable->isManager($uac->userId(), $group->id);
        // If not a group manager keep only the changes that are relative to existing group users.
        if (!$canAdd) {
            $changes = Hash::extract($changes, '{n}[id=/.*/]');
        }

        try {
            return $this->groupsUpdateGroupUsersService->updateGroupUsers($uac, $group->id, $changes);
        } catch (CustomValidationException $e) {
            $group->setError('groups_users', $e->getErrors());
            $this->handleValidationErrors($group);
        }
    }

    /**
     * Update the secrets.
     *
     * @param UserAccessControl $uac The operator
     * @param Group $group The target group
     * @param array $resourcesIds The list of resources ids the groups has access
     * @param array $secrets The list of secret data to add
     * @param array $addedGroupUsers A list of added group users
     * @param array $removedGroupUsers A list of removed group users
     * @return void
     * @throws \Exception If something wrong occurred
     * @throwq CustomValidationException If a secret didn't validate
     */
    private function updateResourcesSecrets(UserAccessControl $uac, Group $group, array $resourcesIds, array $secrets, array $addedGroupUsers, array $removedGroupUsers)
    {
        if (empty($addedGroupUsers) && empty($removedGroupUsers)) {
            return;
        }

        foreach ($resourcesIds as $resourceId) {
            $this->updateResourceSecrets($uac, $group, $resourceId, $secrets);
        }
    }

    /**
     * Update the secrets of a resource.
     *
     * @param UserAccessControl $uac The operator
     * @param Group $group The target group
     * @param string $resourceId The target resource
     * @param array $data The list of secrets to add
     * @return void
     * @throwq CustomValidationException If a secret didn't validate
     * @throws \Exception If something unexpected went wrong
     */
    private function updateResourceSecrets(UserAccessControl $uac, Group $group, string $resourceId, array $data)
    {
        $secretsData = array_filter($data, function ($secretData) use ($resourceId) {
            return $resourceId === Hash::get($secretData, 'resource_id');
        });

        try {
            $this->secretsUpdateSecretsService->updateSecrets($uac, $resourceId, $secretsData);
        } catch (CustomValidationException $e) {
            $group->setError('secrets', $e->getErrors());
            $this->handleValidationErrors($group);
        }
    }

    /**
     * Post group user added.
     *
     * @param UserAccessControl $uac The operator
     * @param Group $group The target group
     * @param array $addedGroupUsers The list of added groups users
     * @return void
     */
    private function postGroupUsersAdded(UserAccessControl $uac, Group $group, array $addedGroupUsers = [])
    {
        foreach ($addedGroupUsers as $groupUser) {
            $eventData = ['groupUser' => $groupUser, 'accessControl' => $uac];
            $event = new Event(self::AFTER_GROUP_USER_ADDED_EVENT_NAME, $this, $eventData);
            $this->groupsTable->getEventManager()->dispatch($event);
        }
    }

    /**
     * Post group users removed.
     *
     * @param UserAccessControl $uac The operator
     * @param Group $group The target group
     * @param array $groupsUsers The list of removed groups users
     * @param array $resourcesIds The list of resources ids the group has access
     * @return void
     */
    private function postGroupUsersRemoved(UserAccessControl $uac, Group $group, array $groupsUsers, array $resourcesIds)
    {
        foreach ($groupsUsers as $groupUser) {
            $this->postGroupUserRemoved($uac, $group, $groupUser, $resourcesIds);
        }
    }

    /**
     * Post group user removed.
     *
     * @param UserAccessControl $uac The operator
     * @param Group $group The target group
     * @param GroupsUser $groupUser The removed group user
     * @param array $resourcesIdsGroupHasAccess The list of resources ids the group has access
     * @return void
     */
    private function postGroupUserRemoved(UserAccessControl $uac, Group $group, GroupsUser $groupUser, array $resourcesIdsGroupHasAccess = [])
    {
        foreach ($resourcesIdsGroupHasAccess as $resourceIdGroupHasAccess) {
            if (!$this->permissionsTable->hasAccess(PermissionsTable::RESOURCE_ACO, $resourceIdGroupHasAccess, $groupUser->user_id)) {
                $this->resourcesTable->deleteLostAccessAssociatedData($resourceIdGroupHasAccess, [$groupUser->user_id]);
            }
        }

        $eventData = ['groupUser' => $groupUser, 'accessControl' => $uac];
        $event = new Event(self::AFTER_GROUP_USER_REMOVED_EVENT_NAME, $this, $eventData);
        $this->groupsTable->getEventManager()->dispatch($event);
    }

    /**
     * Notify the users that they group have been deleted
     *
     * @param UserAccessControl $uac The current user
     * @param Group $group The updated group
     * @param array $addedGroupsUsers changes requested by group editor
     * @param array $updatedGroupsUsers the list of users updated for the group
     * @param array $removedGroupsUsers the list of users removed from the group
     * @return void
     */
    private function notifyUsers(UserAccessControl $uac, Group $group, array $addedGroupsUsers, array $updatedGroupsUsers, array $removedGroupsUsers)
    {
        $event = new Event(static::UPDATE_SUCCESS_EVENT_NAME, $this, [
            'group' => $group,
            'addedGroupsUsers' => $addedGroupsUsers,
            'updatedGroupsUsers' => $updatedGroupsUsers,
            'removedGroupsUsers' => $removedGroupsUsers,
            'userId' => $uac->userId(),
        ]);
        $this->groupsTable->getEventManager()->dispatch($event);
    }
}
