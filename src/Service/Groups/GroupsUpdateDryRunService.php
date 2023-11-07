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
use App\Model\Table\PermissionsTable;
use App\Model\Validation\GroupsUsersChange\GroupsUsersChangeValidator;
use App\Service\Users\UserGetService;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsUpdateDryRunService
{
    /**
     * @var \App\Model\Table\GroupsTable
     */
    private $groupsTable;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $permissionsTable;

    /**
     * @var \App\Model\Table\SecretsTable
     */
    private $secretsTable;

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var \App\Service\Groups\GroupGetService
     */
    private $groupGetService;

    /**
     * @var \App\Service\Users\UserGetService
     */
    private $userGetService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->groupsTable = TableRegistry::getTableLocator()->get('Groups');
        $this->secretsTable = TableRegistry::getTableLocator()->get('Secrets');
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->groupGetService = new GroupGetService();
        $this->userGetService = new UserGetService();
    }

    /**
     * Update a group dry run.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param string $groupId The target group
     * @param array|null $changes The list of group users changes to apply
     * @return array
     * [
     *   secrets <array> The operator secrets that the operator will need to encrypt
     *   secretsNeeded <array> The secrets that need to be encrypted for the users who got access to new resources
     * ]
     * @throws \Exception
     */
    public function dryRun(UserAccessControl $uac, string $groupId, ?array $changes = []): array
    {
        $group = $this->groupGetService->getNotDeletedOrFail($groupId);
        $this->assertChanges($group, $changes);
        $isUacManager = $this->groupsUsersTable->isManager($uac->getId(), $group->id);
        if ($isUacManager) {
            $missingSecrets = $this->getAddedGroupsUsersMissingSecrets($group, $changes);
            $operatorSecretsToEncrypt = $this->getOperatorSecretsToEncrypt($uac, $missingSecrets);
        }

        return [
            'secrets' => $operatorSecretsToEncrypt ?? [],
            'secretsNeeded' => $missingSecrets ?? [],
        ];
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
            throw new ValidationException(__('Could not validate group data.'), $group, $this->groupsTable);
        }
    }

    /**
     * Get the secrets that will require to be encrypted for the users added to the group.
     *
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $changes The list of group users changes.
     * @return array A list of secrets to request to the client
     * [
     *   [
     *     'resource_id' => uuid,
     *     'user_id' => uuid
     *   ],
     *   ...
     * ]
     */
    private function getAddedGroupsUsersMissingSecrets(Group $group, array $changes = []): array
    {
        $missingSecrets = [];

        foreach ($changes as $rowIndexRef => $groupUserData) {
            $id = Hash::get($groupUserData, 'id');
            if (!$id) {
                $userId = Hash::get($groupUserData, 'user_id');
                $this->assertUserToAdd($group, $userId, $rowIndexRef);
                $userMissingSecrets = $this->getAddedGroupUserMissingSecrets($group->id, $userId);
                $missingSecrets = array_merge($missingSecrets, $userMissingSecrets);
            }
        }

        return $missingSecrets;
    }

    /**
     * @param \App\Model\Entity\Group $group The group to update.
     * @param string $userId The identifier of the user to add
     * @param int $rowIndexRef The index of the treated group user in the request data, for error purpose.
     * @return void
     */
    private function assertUserToAdd(Group $group, string $userId, int $rowIndexRef): void
    {
        try {
            $this->userGetService->getActiveNotDeletedOrFail($userId);
        } catch (NotFoundException | BadRequestException $e) {
            $errors = ['user_id' => ['user_exists' => ['Cannot find the user.']]];
            $group->setError('groups_users', [$rowIndexRef => $errors]);
            $this->handleValidationErrors($group);
        }
    }

    /**
     * Get the missing secrets required to add a user to a group.
     *
     * @param string $groupId The identifier of the group to update.
     * @param string $userId The identifier of the user to add
     * @return array
     */
    private function getAddedGroupUserMissingSecrets(string $groupId, string $userId): array
    {
        $missingSecretsResourcesIds = $this->permissionsTable
            ->findAcosAccessesDiffBetweenGroupAndUser(PermissionsTable::RESOURCE_ACO, $groupId, $userId)
            ->all()
            ->extract('aco_foreign_key')
            ->toArray();

        return array_map(function ($resourceId) use ($userId) {
            return [
                'resource_id' => $resourceId,
                'user_id' => $userId,
            ];
        }, $missingSecretsResourcesIds);
    }

    /**
     * Retrieve the secrets that require to be encrypted for a user.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
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
    private function getOperatorSecretsToEncrypt(UserAccessControl $uac, array $usersMissingSecrets = []): array
    {
        if (empty($usersMissingSecrets)) {
            return [];
        }
        $resourceIds = array_unique(Hash::extract($usersMissingSecrets, '{n}.resource_id'));

        // Retrieve the secrets the operator will have to encrypt.
        $query = $this->secretsTable->find()
            ->where([
                'resource_id IN' => $resourceIds,
                'user_id' => $uac->getId() ?? '',
            ])
            ->select(['resource_id', 'data'])
            ->distinct();

        // Format the result.
        $query = $query->all()->map(function ($secret) {
                return [
                    'resource_id' => $secret->resource_id,
                    'data' => $secret->data,
                ];
        });

        return $query->toArray();
    }
}
