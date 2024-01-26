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
use App\Model\Dto\EntitiesChangesDto;
use App\Model\Entity\GroupsUser;
use App\Model\Entity\Secret;
use App\Model\Table\PermissionsTable;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class GroupsUsersAddService
{
    public const AFTER_GROUP_USER_ADDED_EVENT_NAME = 'Service.GroupsUserAdd.afterGroupUserAdded';

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $permissionsTable;

    /**
     * @var \App\Model\Table\SecretsTable
     */
    private $secretsTable;

    /**
     * GroupsUsersAddService constructor.
     */
    public function __construct()
    {
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->secretsTable = TableRegistry::getTableLocator()->get('Secrets');
    }

    /**
     * Add a group user.
     *
     * Note that the service does not check if the operator has the right to update the group, this remains the
     * responsibility of the controllers or top action service.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param array|null $data The group user data to add
     * [
     *   string $group_id The group identifier
     *   string $user_id The user identifier
     *   string $is_admin Is the user group manager
     * ]
     * @param array|null $secretsData The secrets data
     * [
     *   [
     *     string $user_id The user identifier
     *     string $resource_id The resource identifier
     *     string $data The encrypted secret
     *   ],
     *   ...
     * ]
     * @return \App\Model\Dto\EntitiesChangesDto
     * @throws \App\Error\Exception\ValidationException If it could not validate group user data.
     * @throws \App\Error\Exception\ValidationException If it could not validate secrets data.
     * @throws \App\Error\Exception\ValidationException If secrets for resources the user has gotten access by being added to the group are missing.
     * @throws \App\Error\Exception\ValidationException If secrets duplicates are provided.
     * @throws \App\Error\Exception\ValidationException If secrets not for the user added to the group are provided.
     * @throws \App\Error\Exception\ValidationException If secrets not for a resource the user has gotten access by being added to the group are provided.
     * @throws \Exception If an unexpected error occurred.
     */
    public function add(UserAccessControl $uac, ?array $data = [], ?array $secretsData = []): EntitiesChangesDto
    {
        $entitiesChangesDto = new EntitiesChangesDto();
        $groupUser = $this->buildGroupUserEntity($uac, $data);
        $missingAccessResourcesIds = $this->getMissingSecretsResourcesIds($groupUser);

        return $this->groupsUsersTable->getConnection()->transactional(
            function () use ($uac, $groupUser, $secretsData, $missingAccessResourcesIds, $entitiesChangesDto) {
                $this->saveGroupUser($groupUser);
                $entitiesChangesDto->pushAddedEntity($groupUser);
                $secrets = $this->buildSecretsEntities($groupUser, $missingAccessResourcesIds, $secretsData);
                $this->saveSecrets($groupUser, $secrets);
                $entitiesChangesDto->pushAddedEntities($secrets);
                $this->dispatchGroupUserAddedEvent($uac, $groupUser);

                return $entitiesChangesDto;
            }
        );
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
     * @throws \App\Error\Exception\ValidationException If it could not validate group user data.
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

        $groupUser = $this->groupsUsersTable->newEntity($data, ['accessibleFields' => $accessibleFields]);
        if ($groupUser->hasErrors()) {
            $this->handleValidationErrors($groupUser);
        }

        return $groupUser;
    }

    /**
     * Handle group user validation errors.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The group user
     * @throws \App\Error\Exception\ValidationException If the group user has errors.
     * @return void
     */
    private function handleValidationErrors(GroupsUser $groupUser): void
    {
        $msg = __('Could not validate group user data.');
        throw new ValidationException($msg, $groupUser, $this->groupsUsersTable);
    }

    /**
     * Build the secrets entities.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to add.
     * @param array $missingAccessResourcesIds The missing access resources ids.
     * @param array $secretsData The secrets data.
     * @return array
     * @throws \App\Error\Exception\ValidationException If it could not validate secrets data
     * @throws \App\Error\Exception\ValidationException If some required secrets are missing
     * @throws \App\Error\Exception\ValidationException If too many secrets are provided (Duplicate)
     */
    private function buildSecretsEntities(
        GroupsUser $groupUser,
        array $missingAccessResourcesIds,
        array $secretsData = []
    ): array {
        $secrets = [];

        foreach ($secretsData as $secretRowRef => $secretData) {
            $secrets[] = $this->buildSecretEntity($secretRowRef, $secretData, $groupUser, $missingAccessResourcesIds);
        }

        $countProvidedSecrets = count($secrets);
        $countMissingSecrets = count($missingAccessResourcesIds);

        if ($countProvidedSecrets < $countMissingSecrets) {
            $groupUser->setError('secrets', ['all_missing' => 'Some required secrets are missing.']);
            $this->handleValidationErrors($groupUser);
        }

        if ($countProvidedSecrets > $countMissingSecrets) {
            $groupUser->setError('secrets', ['all_missing_only' => 'Too many secrets provided.']);
            $this->handleValidationErrors($groupUser);
        }

        return $secrets;
    }

    /**
     * Get the resources ids which are shared with the group but the user does not have yet a secret for.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to add.
     * @return array
     */
    private function getMissingSecretsResourcesIds(GroupsUser $groupUser): array
    {
        return $this->permissionsTable
            ->findAcosAccessesDiffBetweenGroupAndUser(
                PermissionsTable::RESOURCE_ACO,
                $groupUser->group_id,
                $groupUser->user_id
            )
            ->all()
            ->extract('aco_foreign_key')
            ->toArray();
    }

    /**
     * Build a secret entity.
     *
     * @param int $secretRowRef The secret row reference, to use in error feedback.
     * @param array $secretData The secret data.
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to add.
     * @param array $missingSecretsResourcesIds The user missing secrets resources ids.
     * @return \App\Model\Entity\Secret
     * @throws \App\Error\Exception\ValidationException If it could not validate the secret data.
     * @throws \App\Error\Exception\ValidationException If the secret is not for the user added to the group.
     * @throws \App\Error\Exception\ValidationException If the secret is not for a resource the user has gotten access
     *  by being added to the group.
     */
    private function buildSecretEntity(
        int $secretRowRef,
        array $secretData,
        GroupsUser $groupUser,
        array $missingSecretsResourcesIds = []
    ): Secret {
        $accessibleFields = [
            'resource_id' => true,
            'user_id' => true,
            'data' => true,
        ];
        $secret = $this->secretsTable->newEntity($secretData, ['accessibleFields' => $accessibleFields]);

        if ($secret->hasErrors()) {
            $groupUser->setError('secrets', [$secretRowRef => $secret->getErrors()]);
            $this->handleValidationErrors($groupUser);
        }

        // Ensure the secret is for the user added to the group.
        if ($secret->user_id !== $groupUser->user_id) {
            $errors = ['user_id' => ['same_user' => 'The user_id does not match the user added to the group.']];
            $groupUser->setError('secrets', [$secretRowRef => $errors]);
            $this->handleValidationErrors($groupUser);
        }

        // Ensure the secret is for a resource the user has gotten access by being added to the group.
        if (!in_array($secret->resource_id, $missingSecretsResourcesIds)) {
            $errors = ['resource_id' => ['only_missing' => 'The user already has a secret for the resource.']];
            $groupUser->setError('secrets', [$secretRowRef => $errors]);
            $this->handleValidationErrors($groupUser);
        }

        return $secret;
    }

    /**
     * Save the new group user
     *
     * @param \App\Model\Entity\GroupsUser $groupUser the group user to add.
     * @return void
     * @throws \App\Error\Exception\ValidationException If it could not validate group user data.
     */
    private function saveGroupUser(GroupsUser $groupUser): void
    {
        $this->groupsUsersTable->save($groupUser);
        if ($groupUser->hasErrors()) {
            $this->handleValidationErrors($groupUser);
        }
    }

    /**
     * Save the new secrets.
     * Service/Groups/GroupsUpdateService.php
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to add.
     * @param array $secrets The user secrets to add.
     * @return void
     * @throws \App\Error\Exception\ValidationException If it could not validate secrets data.
     */
    private function saveSecrets(GroupsUser $groupUser, array $secrets = []): void
    {
        foreach ($secrets as $secretRowRef => $secret) {
            /*
             * Disable the SecretsTable check rules in order to reduce the checks redundancy and increase the add
             * user to group service performance. The SecretsTable build rules are already checked at different place as following:
             * - user_id.user_exists: checked when saving the new group user by the build rules of the GroupsUsersTable
             * - user_id.user_is_not_soft_deleted: "
             * - resource_id.resource_exists: checked by logic when using the permissions table to retrieve the missing secrets (getMissingSecretsResourcesIds)
             * - resource_id.resource_is_not_soft_deleted: "
             * - resource_id.has_resource_access: "
             */
            $this->secretsTable->save($secret, ['checkRules' => false]);
            $errors = $secret->getErrors();
            if ($errors) {
                $groupUser->setError('secrets', [$secretRowRef => $errors]);
                $this->handleValidationErrors($groupUser);
            }
        }
    }

    /**
     * Dispatch group user added event.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to remove.
     * @return void
     */
    private function dispatchGroupUserAddedEvent(UserAccessControl $uac, GroupsUser $groupUser): void
    {
        $eventData = ['groupUser' => $groupUser, 'accessControl' => $uac];
        $event = new Event(self::AFTER_GROUP_USER_ADDED_EVENT_NAME, $this, $eventData);
        $this->groupsUsersTable->getEventManager()->dispatch($event);
    }
}
