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

namespace Passbolt\Folders\Service\GroupsUsers;

use App\Model\Entity\GroupsUser;
use App\Model\Table\PermissionsTable;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;

class HandleGroupUserDeletedService
{
    /**
     * Chunk size for `IN (...)` clauses on `folders_relations` writes. Keeps each statement in
     * the range-scan regime so the optimiser can't degrade to a full table scan on a large id list.
     */
    private const DELETE_CHUNK_SIZE = 500;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private PermissionsTable $permissionsTable;

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    private FoldersRelationsTable $foldersRelationsTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
    }

    /**
     * Handle a group user deletion.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The deleted group user.
     * @return void
     * @throws \Exception
     */
    public function handle(GroupsUser $groupUser): void
    {
        $this->removeLostAccessesResourcesFromUserTree($groupUser);
        $this->removeLostAccessesFoldersFromUserTree($groupUser);
    }

    /**
     * Remove resources the user lost access from its tree.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser $groupUser The deleted group user.
     * @return void
     */
    private function removeLostAccessesResourcesFromUserTree(GroupsUser $groupUser): void
    {
        $ids = $this->findLostAccessResourcesIdsQuery($groupUser)
            ->all()
            ->extract('aco_foreign_key')
            ->toArray();
        if (empty($ids)) {
            return;
        }
        // Sort so concurrent transactions acquire folders_relations row locks in the same order (deadlock defence).
        sort($ids, SORT_STRING);
        foreach (array_chunk($ids, self::DELETE_CHUNK_SIZE) as $chunk) {
            $this->foldersRelationsTable->deleteAll([
                'foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE,
                'user_id' => $groupUser->user_id,
                'foreign_id IN' => $chunk,
            ]);
        }
    }

    /**
     * Remove folders the user lost access from its tree.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser $groupUser The deleted group user.
     * @return void
     */
    private function removeLostAccessesFoldersFromUserTree(GroupsUser $groupUser): void
    {
        $ids = $this->findLostAccessFoldersIdsQuery($groupUser)
            ->all()
            ->extract('aco_foreign_key')
            ->toArray();
        if (empty($ids)) {
            return;
        }
        // Sort so concurrent transactions acquire folders_relations row locks in the same order (deadlock defence).
        sort($ids, SORT_STRING);
        foreach (array_chunk($ids, self::DELETE_CHUNK_SIZE) as $chunk) {
            $this->foldersRelationsTable->deleteAll([
                'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
                'user_id' => $groupUser->user_id,
                'foreign_id IN' => $chunk,
            ]);
        }
        $this->moveToRootLostAccessFoldersContent($groupUser->user_id, $ids);
    }

    /**
     * Move folders the user lost access content to the user tree root.
     *
     * @param string $userId The user losing access.
     * @param array<string> $lostAccessFolderIds Folder ids the user lost access to.
     * @return void
     */
    private function moveToRootLostAccessFoldersContent(string $userId, array $lostAccessFolderIds): void
    {
        foreach (array_chunk($lostAccessFolderIds, self::DELETE_CHUNK_SIZE) as $chunk) {
            $this->foldersRelationsTable->updateAll(['folder_parent_id' => null], [
                'user_id' => $userId,
                'folder_parent_id IN' => $chunk,
            ]);
        }
    }

    /**
     * Find the lost access resources ids.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to delete.
     * @return \Cake\ORM\Query\SelectQuery
     */
    private function findLostAccessResourcesIdsQuery(GroupsUser $groupUser): SelectQuery
    {
        return $this->permissionsTable->findAcosAccessesDiffBetweenGroupAndUser(
            PermissionsTable::RESOURCE_ACO,
            $groupUser->group_id,
            $groupUser->user_id,
        );
    }

    /**
     * Find the lost access folders ids.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to delete.
     * @return \Cake\ORM\Query\SelectQuery
     */
    private function findLostAccessFoldersIdsQuery(GroupsUser $groupUser): SelectQuery
    {
        return $this->permissionsTable->findAcosAccessesDiffBetweenGroupAndUser(
            PermissionsTable::FOLDER_ACO,
            $groupUser->group_id,
            $groupUser->user_id,
        );
    }
}
