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
use App\Model\Table\GroupsTable;
use App\Model\Table\GroupsUsersTable;
use App\Model\Table\PermissionsTable;
use App\Model\Table\SecretsTable;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsUpdateDryRunService
{
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
     * @var SecretsTable
     */
    private $secretsTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->groupsTable = TableRegistry::getTableLocator()->get('Groups');
        $this->groupsUpdateGroupUsersService = new GroupsUpdateGroupUsersService();
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->secretsTable = TableRegistry::getTableLocator()->get('Secrets');
    }

    /**
     * Update a group dry run.
     *
     * @param UserAccessControl $uac The current user
     * @param string $groupId The target group
     * @param array $changes The list of group users changes to apply
     * @return array
     * [
     *   secrets <array> The operator secrets that the operator will need to encrypt
     *   secretsNeeded <array> The secrets that need to be encrypted for the users who got access to new resources
     * ]
     * @throws \Exception
     */
    public function dryRun(UserAccessControl $uac, string $groupId, array $changes = [])
    {
        $group = $this->getGroup($groupId);
        $usersMissingSecrets = [];
        $operatorSecretsToEncrypt = [];

        $this->groupsTable->getConnection()->transactional(function () use ($uac, $group, $changes, &$usersMissingSecrets, &$operatorSecretsToEncrypt) {
            $updateGroupsUsersResult = $this->updateGroupUsers($uac, $group, $changes);
            $addedGroupUsers = Hash::get($updateGroupsUsersResult, 'added', []);
            $usersMissingSecrets = $this->getUsersMissingSecrets($group, $addedGroupUsers);
            $operatorSecretsToEncrypt = $this->getOperatorSecretsToEncrypt($uac, $usersMissingSecrets);

            // Don't commit the transaction.
            return false;
        });

        return [
            'secrets' => $operatorSecretsToEncrypt,
            'secretsNeeded' => $usersMissingSecrets,
        ];
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
        if (!$canAdd) {
            return [];
        }

        try {
            return $this->groupsUpdateGroupUsersService->updateGroupUsers($uac, $group->id, $changes);
        } catch (CustomValidationException $e) {
            $group->setError('groups_users', $e->getErrors());
            $this->handleValidationErrors($group);
        }
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
     * Get the secrets that will require to be encrypted for the users added to the group.
     *
     * @param Group $group The target group.
     * @param array $addedGroupUsers The list of group users that have been added to the group
     * @return array A list of secrets to request to the client
     * [
     *   [
     *     'resource_id' => uuid,
     *     'user_id' => uuid
     *   ],
     *   ...
     * ]
     */
    private function getUsersMissingSecrets(Group $group, array $addedGroupUsers = [])
    {
        $usersMissingSecrets = [];

        $resourcesIdsGroupHasAccess = $this->getResourcesIdsGroupHasAccess($group);
        if (empty($resourcesIdsGroupHasAccess)) {
            return $usersMissingSecrets;
        }

        foreach ($addedGroupUsers as $groupUser) {
            $userMissingSecrets = $this->getUserMissingSecrets($groupUser->user_id, $resourcesIdsGroupHasAccess);
            $usersMissingSecrets = array_merge($usersMissingSecrets, $userMissingSecrets);
        }

        return $usersMissingSecrets;
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
     * Get the secrets that will require to be encrypted for a user added to the group.
     *
     * @param string $userId The target user
     * @param array $resourcesIdsGroupHasAccess The resources ids the group has access
     * @return array A list of secrets to request to the client
     * [
     *   [
     *     'resource_id' => uuid,
     *     'user_id' => uuid
     *   ],
     *   ...
     * ]
     */
    private function getUserMissingSecrets(string $userId, array $resourcesIdsGroupHasAccess)
    {
        $userMissingSecrets = [];

        // Retrieve the resources ids a user has a secret for.
        $resourcesIdsUserHasSecrets = $this->secretsTable->find()
            ->where([
                'user_id' => $userId,
                'resource_id IN' => $resourcesIdsGroupHasAccess,
            ])
            ->select('resource_id')
            ->extract('resource_id')
            ->toArray();

        $resourcesIdsUserMissingSecrets = array_diff($resourcesIdsGroupHasAccess, $resourcesIdsUserHasSecrets);

        foreach ($resourcesIdsUserMissingSecrets as $resourceIdUserMissingSecret) {
            $userMissingSecrets[] = ['resource_id' => $resourceIdUserMissingSecret, 'user_id' => $userId];
        }

        return $userMissingSecrets;
    }

    /**
     * Retrieve the secrets that require to be encrypted for a user.
     *
     * @param UserAccessControl $uac The operator
     * @param array $usersMissingSecrets The missing users secrets to encrypt
     * @return array A list of secret with their associated resource
     * [
     *   [
     *     'resource_id' => uuid,
     *     'data' => text
     *   ],
     *   ...
     * ]
     */
    private function getOperatorSecretsToEncrypt(UserAccessControl $uac, array $usersMissingSecrets = [])
    {
        if (empty($usersMissingSecrets)) {
            return [];
        }
        $resourceIds = array_unique(Hash::extract($usersMissingSecrets, '{n}.resource_id'));

        // Retrieve the secrets the operator will have to encrypt.
        $query = $this->secretsTable->find()
            ->where([
                'resource_id IN' => $resourceIds,
                'user_id' => $uac->userId(),
            ])
            ->select(['resource_id', 'data'])
            ->distinct();

        // Format the result.
        $query = $query->map(function ($secret) {
                return [
                    'resource_id' => $secret->resource_id,
                    'data' => $secret->data,
                ];
        });

        return $query->toArray();
    }
}
