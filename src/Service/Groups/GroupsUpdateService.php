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
use App\Model\Dto\EntitiesChangesDto;
use App\Model\Entity\Group;
use App\Model\Entity\GroupsUser;
use App\Model\Entity\Secret;
use App\Model\Validation\GroupsUsersChange\GroupsUsersChangeValidator;
use App\Service\GroupsUsers\GroupsUsersAddService;
use App\Service\GroupsUsers\GroupsUsersDeleteService;
use App\Service\GroupsUsers\GroupsUsersUpdateService;
use App\Service\Resources\ResourcesExpireResourcesServiceInterface;
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
     * @var \App\Service\Resources\ResourcesExpireResourcesServiceInterface
     */
    private ResourcesExpireResourcesServiceInterface $resourcesExpireResourcesService;

    /**
     * @param \App\Service\Resources\ResourcesExpireResourcesServiceInterface $resourcesExpireResourcesService expire resource service
     */
    public function __construct(
        ResourcesExpireResourcesServiceInterface $resourcesExpireResourcesService
    ) {
        $this->resourcesExpireResourcesService = $resourcesExpireResourcesService;
        $this->groupsTable = TableRegistry::getTableLocator()->get('Groups');
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
     * @return \App\Model\Dto\EntitiesChangesDto
     * @throws \Exception If an unexpected error occurred.
     */
    public function update(
        UserAccessControl $uac,
        string $groupId,
        array $metaData,
        ?array $changes = [],
        ?array $secrets = []
    ): EntitiesChangesDto {
        $group = $this->groupGetService->getNotDeletedOrFail($groupId);
        $this->assertChanges($group, $changes);
        $entitiesChangesDto = new EntitiesChangesDto();
        $this->groupsTable->getConnection()->transactional(
            function () use ($uac, $group, $metaData, $changes, $secrets, $entitiesChangesDto) {
                $this->updateMetaData($uac, $group, $metaData);
                $entitiesChangesDto->pushUpdatedEntity($group);
                $entitiesChangesDto->merge($this->addGroupsUsers($uac, $group, $changes, $secrets));
                $entitiesChangesDto->merge($this->updateGroupsUsers($uac, $group, $changes));
                $entitiesChangesDto->merge($this->deleteGroupsUsers($uac, $group, $changes));
                $this->resourcesExpireResourcesService->expireResourcesForSecrets(
                    $entitiesChangesDto->getDeletedEntities(Secret::class)
                );
                $this->notifyUsers($uac, $group, $entitiesChangesDto);
            }
        );

        return $entitiesChangesDto;
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
     * @return \App\Model\Dto\EntitiesChangesDto Entities changes applied following the addition of users to groups
     * @throws \Exception If an unexpected error occurred.
     */
    private function addGroupsUsers(
        UserAccessControl $uac,
        Group $group,
        array $changes,
        array $secretsData
    ): EntitiesChangesDto {
        $entitiesChangesDto = new EntitiesChangesDto();
        $isUacManager = $this->groupsUsersTable->isManager($uac->getId(), $group->id);

        if (!$isUacManager) {
            return $entitiesChangesDto;
        }

        foreach ($changes as $rowIndexRef => $groupUserData) {
            if (GroupsUsersChangeValidator::isAddChange($groupUserData)) {
                $addedGroupsUserEntitiesChangesDto = $this->addGroupUser(
                    $uac,
                    $group,
                    $groupUserData,
                    $secretsData,
                    $rowIndexRef
                );
                $entitiesChangesDto->merge($addedGroupsUserEntitiesChangesDto);
            }
        }

        return $entitiesChangesDto;
    }

    /**
     * Add a group user.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $groupUserData The data of the group user to add.
     * @param array $secretsData The data of the secrets to add.
     * @param int $rowIndexRef The index of the treated group user in the request data, for error purpose.
     * @return \App\Model\Dto\EntitiesChangesDto Entities changes applied following the addition of the user to group
     * @throws \App\Error\Exception\ValidationException If a validation error occured while inserting the new group user.
     * @throws \Exception If an unexpected error occurred.
     */
    private function addGroupUser(
        UserAccessControl $uac,
        Group $group,
        array $groupUserData,
        array $secretsData,
        int $rowIndexRef
    ): EntitiesChangesDto {
        $userId = Hash::get($groupUserData, 'user_id');
        $groupUserData['group_id'] = $group->id;
        $userSecretsData = Hash::extract($secretsData, "{n}[user_id={$userId}]");
        $dto = new EntitiesChangesDto();

        try {
            $dto = $this->groupsUsersCreateService->add($uac, $groupUserData, $userSecretsData);
        } catch (ValidationException $e) {
            $group->setError('groups_users', [$rowIndexRef => $e->getErrors()]);
            $this->handleValidationErrors($group);
        }

        return $dto;
    }

    /**
     * Update groups users.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $changes The list of group users changes.
     * @return \App\Model\Dto\EntitiesChangesDto Array of updated GroupUser
     */
    private function updateGroupsUsers(UserAccessControl $uac, Group $group, array $changes): EntitiesChangesDto
    {
        $entitiesChangesDto = new EntitiesChangesDto();
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
         * check if there is a group manager, therefore sort the changes in order to get changes that grant the manager role
         * on top of the list.
         * Preserve the changes index for later associated error treatment.
         */
        $this->sortGroupUsersUpdateChanges($updateGroupsUsersChanges);

        foreach ($updateGroupsUsersChanges as $rowIndexRef => $updateGroupsUsersChange) {
            $groupUser = $this->updateGroupUser($uac, $group, $updateGroupsUsersChange, $rowIndexRef);
            $entitiesChangesDto->pushUpdatedEntity($groupUser);
        }

        return $entitiesChangesDto;
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
     * @return ?\App\Model\Entity\GroupsUser
     */
    private function updateGroupUser(
        UserAccessControl $uac,
        Group $group,
        array $groupUserData,
        int $rowIndexRef
    ): ?GroupsUser {
        $groupUserToUpdate = $this->getAndAssertGroupUserFromData($group, $groupUserData, $rowIndexRef);

        // Return if there is no group role change requested.
        $isAdmin = Hash::get($groupUserData, 'is_admin');
        if ($groupUserToUpdate->is_admin === $isAdmin) {
            return null;
        }

        try {
            return $this->groupsUsersUpdateService->update($uac, $groupUserToUpdate->id, $groupUserData);
        } catch (ValidationException $e) {
            $group->setError('groups_users', [$rowIndexRef => $e->getErrors() ?? $e->getMessage()]);
            $this->handleValidationErrors($group);
        } catch (RecordNotFoundException $e) {
            $group->setError('groups_users', [$rowIndexRef => ['id' => ['exists' => ['Cannot find the group user.']]]]);
            $this->handleValidationErrors($group);
        }

        return null;
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

        if ($groupUser->group_id !== $group->id) {
            $group->setError('groups_users', [$rowIndexRef => ['id' => ['exists' => 'Cannot find the group user.']]]);
            $this->handleValidationErrors($group);
        }

        return $groupUser;
    }

    /**
     * Delete groups users.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $changes The list of group users changes.
     * @return \App\Model\Dto\EntitiesChangesDto
     */
    private function deleteGroupsUsers(UserAccessControl $uac, Group $group, array $changes): EntitiesChangesDto
    {
        $entitiesChangesDto = new EntitiesChangesDto();

        foreach ($changes as $rowIndexRef => $change) {
            if (GroupsUsersChangeValidator::isDeleteChange($change)) {
                $deletedGroupUserEntitiesChangesDto = $this->deleteGroupUser($uac, $group, $change, $rowIndexRef);
                $entitiesChangesDto->merge($deletedGroupUserEntitiesChangesDto);
            }
        }

        return $entitiesChangesDto;
    }

    /**
     * Delete a group user
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $groupUserData The data of the group user to delete.
     * @param int $rowIndexRef The index of the treated group user in the request data, for error purpose.
     * @return \App\Model\Dto\EntitiesChangesDto
     * @throws \Exception
     */
    private function deleteGroupUser(
        UserAccessControl $uac,
        Group $group,
        array $groupUserData,
        int $rowIndexRef
    ): EntitiesChangesDto {
        $groupUser = $this->getAndAssertGroupUserFromData($group, $groupUserData, $rowIndexRef);
        $dto = new EntitiesChangesDto();

        try {
            $dto = $this->groupsUsersRemoveService->delete($uac, $groupUser->id);
        } catch (ValidationException $e) {
            $group->setError('groups_users', [$rowIndexRef => $e->getErrors() ?? $e->getMessage()]);
            $this->handleValidationErrors($group);
        } catch (RecordNotFoundException $e) {
            $group->setError('groups_users', [$rowIndexRef => ['id' => ['exists' => ['Cannot find the group user.']]]]);
            $this->handleValidationErrors($group);
        }

        return $dto;
    }

    /**
     * Notify the users that they group have been deleted
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param \App\Model\Entity\Group $group The updated group
     * @param \App\Model\Dto\EntitiesChangesDto $entitiesChanges the list of added groups users
     * @return void
     */
    private function notifyUsers(
        UserAccessControl $uac,
        Group $group,
        EntitiesChangesDto $entitiesChanges
    ): void {
        $event = new Event(static::UPDATE_SUCCESS_EVENT_NAME, $this, [
            'group' => $group,
            'entitiesChanges' => $entitiesChanges,
            'userId' => $uac->getId(),
        ]);
        $this->groupsTable->getEventManager()->dispatch($event);
    }
}
