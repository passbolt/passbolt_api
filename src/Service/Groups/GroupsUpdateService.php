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

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Group;
use App\Model\Entity\GroupsUser;
use App\Model\Table\PermissionsTable;
use App\Service\Secrets\SecretsUpdateSecretsService;
use App\Utility\UserAccessControl;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;

class GroupsUpdateService
{
    use ModelAwareTrait;

    public const AFTER_GROUP_USER_ADDED_EVENT_NAME = 'Service.GroupsUpdate.afterGroupUserAdded';
    public const AFTER_GROUP_USER_REMOVED_EVENT_NAME = 'Service.GroupsUpdate.afterGroupUserRemoved';
    public const UPDATE_SUCCESS_EVENT_NAME = 'GroupsUpdateController.update.success';

    /**
     * @var \App\Model\Table\GroupsTable
     */
    private $Groups;

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $GroupsUsers;

    /**
     * @var \App\Service\Groups\GroupsUpdateGroupUsersService
     */
    private $groupsUpdateGroupUsersService;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $Permissions;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    private $Resources;

    /**
     * @var \App\Service\Secrets\SecretsUpdateSecretsService
     */
    private $secretsUpdateSecretsService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->loadModel('Groups');
        $this->loadModel('GroupsUsers');
        $this->loadModel('Permissions');
        $this->loadModel('Resources');
        $this->groupsUpdateGroupUsersService = new GroupsUpdateGroupUsersService();
        $this->secretsUpdateSecretsService = new SecretsUpdateSecretsService();
    }

    /**
     * Update a group.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param string $groupId The target group
     * @param array $metaData The group meta data to update
     * @param array|null $changes The list of group users changes to apply
     * @param array|null $secrets The list of new secrets to add
     * @return \App\Model\Entity\Group
     * @throws \Exception
     */
    public function update(
        UserAccessControl $uac,
        string $groupId,
        array $metaData,
        ?array $changes = [],
        ?array $secrets = []
    ): Group {
        $group = $this->getGroup($groupId);

        $this->Groups->getConnection()->transactional(
            function () use ($uac, $group, $metaData, $changes, $secrets) {
                $rIds = $this->getResourcesIdsGroupHasAccess($group);
                $this->updateMetaData($uac, $group, $metaData);
                $updateGroupsUsersResult = $this->updateGroupUsers($uac, $group, $changes);
                $addedGroupUsers = Hash::get($updateGroupsUsersResult, 'added', []);
                $updatedGroupUsers = Hash::get($updateGroupsUsersResult, 'updated', []);
                $removedGroupUsers = Hash::get($updateGroupsUsersResult, 'removed', []);
                $this->updateResourcesSecrets($uac, $group, $rIds, $secrets, $addedGroupUsers, $removedGroupUsers);
                $this->postGroupUsersAdded($uac, $group, $addedGroupUsers);
                $this->postGroupUsersRemoved($uac, $group, $removedGroupUsers, $rIds);
                $this->notifyUsers($uac, $group, $addedGroupUsers, $updatedGroupUsers, $removedGroupUsers);
            }
        );

        return $group;
    }

    /**
     * Retrieve the group.
     *
     * @param string $groupId The target group to retrieve
     * @return \App\Model\Entity\Group
     * @throws \Cake\Http\Exception\NotFoundException If the group does not exist.
     */
    private function getGroup(string $groupId): Group
    {
        /** @var \App\Model\Entity\Group|null $group */
        $group = $this->Groups->findById($groupId)->first();
        if (empty($group) || $group->get('deleted')) {
            throw new NotFoundException(__('The group does not exist.'));
        }

        return $group;
    }

    /**
     * Retrieve the resources ids a group has access.
     *
     * @param \App\Model\Entity\Group $group The target group
     * @return array The list of resources ids
     */
    private function getResourcesIdsGroupHasAccess(Group $group): array
    {
        return $this->Permissions->findAllByAro(PermissionsTable::RESOURCE_ACO, $group->id)
            ->select('aco_foreign_key')
            ->extract('aco_foreign_key')
            ->toArray();
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
        $metaData['modified_by'] = $uac->getId();
        $this->Groups->patchEntity($group, $metaData, $groupPatchOptions);
        $this->Groups->save($group);
        $this->handleValidationErrors($group);
    }

    /**
     * Handle group validation errors.
     *
     * @param \App\Model\Entity\Group $group The target group
     * @return void
     * @throws \App\Error\Exception\ValidationException If the provided data does not validate.
     */
    private function handleValidationErrors(Group $group): void
    {
        $errors = $group->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate group data.'), $group, $this->Groups);
        }
    }

    /**
     * Update the group users of a group.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param \App\Model\Entity\Group $group The target group
     * @param array $changes The list of group users changes to apply
     * @return array
     * [
     *   added => <array> List of added group users
     *   deleted => <array> List of deleted group users
     *   updated => <array> List of updated group users
     * ]
     * @throws \Exception If something unexpected occurred
     */
    private function updateGroupUsers(UserAccessControl $uac, Group $group, array $changes): array
    {
        if (empty($changes)) {
            return [];
        }

        // Only a group manager can add new members to a group.
        $canAdd = $this->GroupsUsers->isManager($uac->getId(), $group->id);
        // If not a group manager keep only the changes that are relative to existing group users.
        if (!$canAdd) {
            $changes = Hash::extract($changes, '{n}[id=/.*/]');
        }

        try {
            return $this->groupsUpdateGroupUsersService->updateGroupUsers($uac, $group->id, $changes);
        } catch (CustomValidationException $e) {
            $group->setError('groups_users', $e->getErrors());
            $this->handleValidationErrors($group);

            return [];
        }
    }

    /**
     * Update the secrets.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \App\Model\Entity\Group $group The target group
     * @param array $resourcesIds The list of resources ids the groups has access
     * @param array $secrets The list of secret data to add
     * @param array $addedGroupUsers A list of added group users
     * @param array $removedGroupUsers A list of removed group users
     * @return void
     * @throws \Exception If something wrong occurred
     * @throwq CustomValidationException If a secret didn't validate
     */
    private function updateResourcesSecrets(
        UserAccessControl $uac,
        Group $group,
        array $resourcesIds,
        array $secrets,
        array $addedGroupUsers,
        array $removedGroupUsers
    ): void {
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
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \App\Model\Entity\Group $group The target group
     * @param string $resourceId The target resource
     * @param array $data The list of secrets to add
     * @return void
     * @throwq CustomValidationException If a secret didn't validate
     * @throws \Exception If something unexpected went wrong
     */
    private function updateResourceSecrets(UserAccessControl $uac, Group $group, string $resourceId, array $data): void
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
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \App\Model\Entity\Group $group The target group
     * @param array $addedGroupUsers The list of added groups users
     * @return void
     */
    private function postGroupUsersAdded(UserAccessControl $uac, Group $group, array $addedGroupUsers = []): void
    {
        foreach ($addedGroupUsers as $groupUser) {
            $eventData = ['groupUser' => $groupUser, 'accessControl' => $uac];
            $event = new Event(self::AFTER_GROUP_USER_ADDED_EVENT_NAME, $this, $eventData);
            $this->Groups->getEventManager()->dispatch($event);
        }
    }

    /**
     * Post group users removed.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \App\Model\Entity\Group $group The target group
     * @param array $groupsUsers The list of removed groups users
     * @param array $resourcesIds The list of resources ids the group has access
     * @return void
     */
    private function postGroupUsersRemoved(
        UserAccessControl $uac,
        Group $group,
        array $groupsUsers,
        array $resourcesIds
    ): void {
        foreach ($groupsUsers as $groupUser) {
            $this->postGroupUserRemoved($uac, $group, $groupUser, $resourcesIds);
        }
    }

    /**
     * Post group user removed.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \App\Model\Entity\Group $group The target group
     * @param \App\Model\Entity\GroupsUser $groupUser The removed group user
     * @param array $resourcesIdsGroupHasAccess The list of resources ids the group has access
     * @return void
     */
    private function postGroupUserRemoved(
        UserAccessControl $uac,
        Group $group,
        GroupsUser $groupUser,
        array $resourcesIdsGroupHasAccess = []
    ): void {
        foreach ($resourcesIdsGroupHasAccess as $resourceIdGroupHasAccess) {
            $acoType = PermissionsTable::RESOURCE_ACO;
            $userId = $groupUser->user_id;
            if (!$this->Permissions->hasAccess($acoType, $resourceIdGroupHasAccess, $userId)) {
                $this->Resources->deleteLostAccessAssociatedData($resourceIdGroupHasAccess, [$userId]);
            }
        }

        $eventData = ['groupUser' => $groupUser, 'accessControl' => $uac];
        $event = new Event(self::AFTER_GROUP_USER_REMOVED_EVENT_NAME, $this, $eventData);
        $this->Groups->getEventManager()->dispatch($event);
    }

    /**
     * Notify the users that they group have been deleted
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param \App\Model\Entity\Group $group The updated group
     * @param array $addedGroupsUsers changes requested by group editor
     * @param array $updatedGroupsUsers the list of users updated for the group
     * @param array $removedGroupsUsers the list of users removed from the group
     * @return void
     */
    private function notifyUsers(
        UserAccessControl $uac,
        Group $group,
        array $addedGroupsUsers,
        array $updatedGroupsUsers,
        array $removedGroupsUsers
    ): void {
        $event = new Event(static::UPDATE_SUCCESS_EVENT_NAME, $this, [
            'group' => $group,
            'addedGroupsUsers' => $addedGroupsUsers,
            'updatedGroupsUsers' => $updatedGroupsUsers,
            'removedGroupsUsers' => $removedGroupsUsers,
            'userId' => $uac->getId(),
        ]);
        $this->Groups->getEventManager()->dispatch($event);
    }
}
