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
use App\Model\Dto\EntitiesChangesDto;
use App\Model\Entity\GroupsUser;
use App\Model\Table\PermissionsTable;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class GroupsUsersDeleteService
{
    public const AFTER_GROUP_USER_DELETED_EVENT_NAME = 'Service.GroupsUserDelete.afterGroupUserDeleted';

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
     * @var \App\Model\Table\FavoritesTable
     */
    private $favoritesTable;

    /**
     * GroupsUsersRemoveService constructor.
     */
    public function __construct()
    {
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->secretsTable = TableRegistry::getTableLocator()->get('Secrets');
        $this->favoritesTable = TableRegistry::getTableLocator()->get('Favorites');
    }

    /**
     * Delete a group user.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $groupUserId The group user id to delete
     * @return \App\Model\Dto\EntitiesChangesDto
     * @throws \App\Error\Exception\ValidationException if it cannot find group user.
     * @throws \App\Error\Exception\ValidationException Cannot delete the last group manager.
     * @throws \Exception If something went wrong
     */
    public function delete(UserAccessControl $uac, string $groupUserId): EntitiesChangesDto
    {
        $entitiesChangesDto = new EntitiesChangesDto();
        $groupUser = $this->groupsUsersTable->get($groupUserId);
        $this->assertAtLeastOneGroupManager($groupUser);

        $this->groupsUsersTable->getConnection()->transactional(function () use ($uac, $groupUser, $entitiesChangesDto) { //phpcs:ignore
            $this->groupsUsersTable->delete($groupUser);
            $entitiesChangesDto->pushDeletedEntity($groupUser);
            $deletedSecrets = $this->deleteLostAccessAssociatedSecrets($groupUser);
            $entitiesChangesDto->pushDeletedEntities($deletedSecrets);
            $this->deleteLostAccessAssociatedFavorites($groupUser);
            $this->dispatchGroupUserRemovedEvent($uac, $groupUser);
        });

        return $entitiesChangesDto;
    }

    /**
     * Assert that the group to remove the group user in will have at least one manager after removing the group user.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to check the group for
     * @return void
     * @throws \App\Error\Exception\ValidationException Cannot delete the last group manager.
     */
    private function assertAtLeastOneGroupManager(GroupsUser $groupUser): void
    {
        if (!$groupUser->is_admin) {
            return;
        }

        $groupManagersCount = $this->groupsUsersTable->findByGroupIdAndIsAdmin($groupUser->group_id, true)
            ->count();

        if ($groupManagersCount === 1) {
            $groupUser->setError('is_admin', ['at_least_one_group_manager' => 'Cannot delete the last group manager.']);
            throw new ValidationException('Cannot delete group user.', $groupUser);
        }
    }

    /**
     * Delete the secrets for the resources the user lost access after being removed from the group.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to delete.
     * @return array<\App\Model\Entity\Secret>
     */
    private function deleteLostAccessAssociatedSecrets(GroupsUser $groupUser): array
    {
        $lostAccessSecretsConditions = [
            'user_id' => $groupUser->user_id,
            'resource_id IN' => $this->findLostAccessResourcesIdsQuery($groupUser),
        ];
        $lostAccessSecrets = $this->secretsTable->find()
            ->select(['id', 'resource_id', 'user_id'])
            ->where($lostAccessSecretsConditions)
            ->all()->toArray();
        $this->secretsTable->deleteMany($lostAccessSecrets);

        return $lostAccessSecrets;
    }

    /**
     * Find the lost access resources ids.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to delete.
     * @return \Cake\ORM\Query
     */
    private function findLostAccessResourcesIdsQuery(GroupsUser $groupUser): Query
    {
        return $this->permissionsTable->findAcosAccessesDiffBetweenGroupAndUser(
            PermissionsTable::RESOURCE_ACO,
            $groupUser->group_id,
            $groupUser->user_id,
        );
    }

    /**
     * Delete the favorites for the resources the user lost access after being removed from the group.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to delete.
     * @return void
     */
    private function deleteLostAccessAssociatedFavorites(GroupsUser $groupUser): void
    {
        $this->favoritesTable->deleteAll([
            'user_id' => $groupUser->user_id,
            'foreign_key IN' => $this->findLostAccessResourcesIdsQuery($groupUser),
        ]);
    }

    /**
     * Dispatch group user deleted event.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to delete.
     * @return void
     */
    private function dispatchGroupUserRemovedEvent(UserAccessControl $uac, GroupsUser $groupUser): void
    {
        $eventData = ['groupUser' => $groupUser, 'accessControl' => $uac];
        $event = new Event(self::AFTER_GROUP_USER_DELETED_EVENT_NAME, $this, $eventData);
        $this->groupsUsersTable->getEventManager()->dispatch($event);
    }
}
