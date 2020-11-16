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

namespace Passbolt\Folders\Service\FoldersRelations;

use App\Utility\UserAccessControl;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;

class FoldersRelationsAddItemToUserTreeService
{
    /**
     * @var \Passbolt\Folders\Model\Table\FoldersTable
     */
    private $foldersTable;

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsCreateService
     */
    private $foldersRelationsCreateService;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsDetectStronglyConnectedComponents
     */
    private $folderRelationsDetectStronglyConnectedComponents;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsRepairStronglyConnectedComponents
     */
    private $foldersRelationsRepairStronglyConnectedComponents;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->foldersRelationsCreateService = new FoldersRelationsCreateService();
        $this->folderRelationsDetectStronglyConnectedComponents =
            new FoldersRelationsDetectStronglyConnectedComponents();
        $this->foldersRelationsRepairStronglyConnectedComponents =
            new FoldersRelationsRepairStronglyConnectedComponents();
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
    }

    /**
     * Add an item to a user folders tree.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The entity model
     * @param string $foreignId The item id
     * @param string $userId The target user id the item is added for
     * @return void
     * @throws \Exception If an unexpected error occurred
     */
    public function addItemToUserTree(
        UserAccessControl $uac,
        string $foreignModel,
        string $foreignId,
        string $userId
    ): void {
        $exists = $this->foldersRelationsTable->isItemInUserTree($userId, $foreignId);
        if ($exists) {
            return;
        }

        $this->foldersRelationsTable->getConnection()->transactional(
            function () use (&$uac, $foreignModel, $foreignId, $userId) {
            // Add the item at the root of the target user tree.
                $this->foldersRelationsCreateService
                ->create($uac, $foreignModel, $foreignId, $userId, FoldersRelation::ROOT);
            // Then reconstruct the user tree.
                $this->reconstructUserTree($uac, $foreignModel, $foreignId, $userId);
            }
        );
    }

    /**
     * Reconstruct the target user tree for the newly added item in order to ensure a common organization among all
     * passbolt users. When an item is shared with a user, its organization is also shared along with it. The ancestor
     * and children associations of this item in other users trees should be reflected in the target user tree.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The entity model
     * @param string $foreignId The item id
     * @param string $userId The target user id the item is added for
     * @return void
     */
    private function reconstructUserTree(
        UserAccessControl $uac,
        string $foreignModel,
        string $foreignId,
        string $userId
    ): void {
        $changes = $this->getReconstructChanges($uac, $foreignModel, $foreignId, $userId);
        $changes = $this->sortReconstructChangesByPriorities($uac, $changes);

        foreach ($changes as $change) {
            if ($change['type'] === 'parent') {
                $this->reconstructParent($uac, $foreignModel, $foreignId, $userId, $change);
            } elseif ($change['type'] === 'child') {
                $this->reconstructChild($uac, $foreignId, $userId, $change);
            } else {
                throw new InternalErrorException('Change type must be parent or child.');
            }
        }
    }

    /**
     * Returns a list of changes to apply to the user tree in order to integrate the newly added item. The list of
     * changes will contain potential parents and children associations to apply to the target user tree. Each
     * proposed change will come with metrics in order to define later the priority of execution.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The entity model
     * @param string $foreignId The item id
     * @param string $userId The target user id the item is added for
     * @return array
     */
    private function getReconstructChanges(
        UserAccessControl $uac,
        string $foreignModel,
        string $foreignId,
        string $userId
    ): array {
        $changes = $this->getReconstructFolderParentChanges($uac, $foreignId, $userId);
        if ($foreignModel === FoldersRelation::FOREIGN_MODEL_FOLDER) {
            $changes = array_merge($changes, $this->getReconstructFolderChildrenChanges($uac, $foreignId, $userId));
        }

        return $changes;
    }

    /**
     * Returns a list of parents changes to apply to the user tree in order to associate the newly added item with a
     * parent already present in the user tree. This function does not decide which parent will be chosen to associate
     * the newly added item with, but it will return metrics that will help to define later the priority of execution.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignId The item id
     * @param string $userId The target user id the item is added for
     * @return array
     * [
     *   string $type The type of change, "parent" here
     *   string $foreignId The target folder parent id
     *   bool $inOperatorTree Has the operator this representation
     *   int $usedCount How many user have this representation
     *   string $created The target folder created date
     * ]
     */
    private function getReconstructFolderParentChanges(
        UserAccessControl $uac,
        string $foreignId,
        string $userId
    ): array {
        $changes = [];
        $folderParentsIds = $this->getPotentialParentIdsFor($foreignId, $userId);

        foreach ($folderParentsIds as $folderParentId) {
            $inOperatorTree = $this->foldersRelationsTable->isItemInUserTree($uac->getId(), $folderParentId);
            $usedCount = $this->foldersRelationsTable->countRelationUsage($foreignId, $folderParentId);
            $created = $this->foldersTable->getCreatedDate($folderParentId);

            $changes[] = [
                'type' => 'parent',
                'foreignId' => $folderParentId,
                'inOperatorTree' => $inOperatorTree,
                'usedCount' => $usedCount,
                'created' => $created,
            ];
        }

        return $changes;
    }

    /**
     * Returns a list of folders that could be a potential parent of the newly added item in the target user tree.
     *
     * @param string $foreignId The item id
     * @param string $userId The target user id the item is added for
     * @return array<string>
     */
    private function getPotentialParentIdsFor(string $foreignId, string $userId): array
    {
        // R = All the folders that are a parent of the newly added item in other users trees and are also visible by
        //     the target user.
        //
        // Details :
        // USERS_ITEMS = All the items the target user can see
        // FOLDER_PARENTS = All the parents of the newly added item in all the users trees
        // R = USERS_ITEMS ⋂ FOLDER_PARENTS

        // USERS_ITEMS
        $userItems = $this->foldersRelationsTable
            ->findByUserIdAndForeignModel($userId, FoldersRelation::FOREIGN_MODEL_FOLDER);
        // FOLDER_PARENTS
        $query = $this->foldersRelationsTable->findByForeignId($foreignId);
        // R = USERS_ITEMS ⋂ FOLDER_PARENTS
        $query->where([
            'folder_parent_id IN' => $userItems->select('foreign_id'),
        ]);

        return $query->select(['folder_parent_id'])
            ->distinct('folder_parent_id')
            ->extract('folder_parent_id')
            ->toArray();
    }

    /**
     * Returns a list of children changes to apply to the user tree in order to associate the newly added item with
     * children items already present in the user tree. The priority of execution is not decided by this function but
     * metrics are associated to each change to help later to decide the order. Priority is important in the sense that
     * changes can be indirectly in conflict with each other. By instance if when a first change is applied, if this
     * change create an indirect conflict (SCCs by instance) with a third user tree, then while resolving this conflict
     * it can make a second change in the list not valid anymore.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignId The item id
     * @param string $userId The target user id the item is added for
     * @return array
     * [
     *   string $type The type of change, "parent" here
     *   string $foreignId The target folder parent id
     *   bool $inOperatorTree Has the operator this representation
     *   int $usedCount How many user have this representation
     *   string $created The target item created date
     * ]
     */
    private function getReconstructFolderChildrenChanges(
        UserAccessControl $uac,
        string $foreignId,
        string $userId
    ): array {
        $changes = [];
        $folderChildrenIds = $this->getPotentialChildrenIdsFor($foreignId, $userId);
        foreach ($folderChildrenIds as $folderChildId) {
            $inOperatorTree = $this->foldersRelationsTable
                ->isItemInUserTree($uac->getId(), $folderChildId);
            $usedCount = $this->foldersRelationsTable
                ->findByForeignIdAndFolderParentId($foreignId, $folderChildId)
                ->count();
            $created = $this->foldersTable
                ->findById($folderChildId)
                ->select('created')
                ->extract('created')
                ->first();
            $changes[] = [
                'type' => 'child',
                'foreignId' => $folderChildId,
                'inOperatorTree' => $inOperatorTree,
                'usedCount' => $usedCount,
                'created' => $created,
            ];
        }

        return $changes;
    }

    /**
     * Returns a list of items that could be potential children of the newly added item in the target user tree.
     *
     * @param string $foreignId The item id
     * @param string $userId The target user id the item is added for
     * @return array<string>
     */
    private function getPotentialChildrenIdsFor(string $foreignId, string $userId): array
    {
        // R = All the folders that are a parent of the newly added item in other users trees and are also visible by
        //     the target user.
        //
        // Details :
        // USERS_ITEMS = All the items the target user can see
        // CHILDREN = All the children of the newly added item in all the users trees
        // R = USERS_ITEMS ⋂ CHILDREN

        // USERS_ITEMS = All the items the target user can see
        $userItems = $this->foldersRelationsTable
            ->findByUserIdAndForeignModel($userId, FoldersRelation::FOREIGN_MODEL_FOLDER);
        // CHILDREN
        $query = $this->foldersRelationsTable->findByFolderParentId($foreignId);
        // R = USERS_ITEMS ⋂ CHILDREN
        $query->where(['foreign_id IN' => $userItems->select('foreign_id')]);

        return $query->select(['foreign_id'])
            ->distinct('foreign_id')
            ->extract('foreign_id')
            ->toArray();
    }

    /**
     * Sort the list of changes by priorities:
     * 1. Operator;
     * 2. Most used;
     * 3. Oldest;
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param array $changes The list of changes to sort
     * @return array
     */
    private function sortReconstructChangesByPriorities(UserAccessControl $uac, array $changes)
    {
        usort($changes, function ($changeA, $changeB) use ($uac) {
            // Operator relations should be applied in priority.
            if ($changeA['inOperatorTree']) {
                return -1;
            } elseif ($changeB['inOperatorTree']) {
                return 1;
            }
            // Otherwise most used relations should be applied in priority.
            if ($changeA['usedCount'] > $changeB['usedCount']) {
                return -1;
            } elseif ($changeA['usedCount'] < $changeB['usedCount']) {
                return 1;
            }
            // Otherwise oldest relations should be applied in priority.
            if ($changeA['created'] < $changeB['created']) {
                return -1;
            } elseif ($changeB['created'] < $changeA['created']) {
                return 1;
            }

            return 1;
        });

        return $changes;
    }

    /**
     * Reconstruct the newly added item parent in the target user tree.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The entity model
     * @param string $foreignId The item id
     * @param string $userId The target user id the item is added for
     * @param array $change The change
     * @return void
     */
    private function reconstructParent(
        UserAccessControl $uac,
        string $foreignModel,
        string $foreignId,
        string $userId,
        array $change
    ): void {
        // If no one see the newly added item in the proposed parent, then this change is not valid anymore.
        // It happens when the item has already been organized already by a change with a higher priority.
        $exists = $this->foldersRelationsTable
            ->exists(['foreign_id' => $foreignId, 'folder_parent_id' => $change['foreignId']]);
        if (!$exists) {
            return;
        }

        // Move the item to the root of users seeing the item in another potential folder parent. Except for people
        // seeing the item in the proposed parent folder.
        $potentialFolderParentsIds = $this->getPotentialParentIdsFor($foreignId, $userId);
        array_splice($potentialFolderParentsIds, array_search($change['foreignId'], $potentialFolderParentsIds), 1);
        if (!empty($potentialFolderParentsIds)) {
            $this->foldersRelationsTable->moveItemFrom($foreignId, $potentialFolderParentsIds, FoldersRelation::ROOT);
        }

        // Move the item to the proposed parent folder for all users seeing the proposed parent folder and the target
        // item.
        $usersIdsHavingAccessToFolderParent = $this->foldersRelationsTable
            ->getUsersIdsHavingAccessToMultipleItems([$foreignId, $change['foreignId']]);
        $this->foldersRelationsTable
            ->moveItemFor($foreignId, $usersIdsHavingAccessToFolderParent, $change['foreignId']);

        // If the newly added item is a folder, detect and fix conflicts.
        if ($foreignModel === FoldersRelation::FOREIGN_MODEL_FOLDER) {
            $this->detectAndRepairConflicts($uac, $userId, $usersIdsHavingAccessToFolderParent);
        }
    }

    /**
     * Detect an repair conflicts after a change is applied to user trees.
     *
     * Two kind of conflicts can be identified.
     * - A cycle in the impacted users trees. The newly added folder cannot be it's own grand father. This case can only
     *   happen when a personal folder is involved.
     * - A cycle between two user trees, also called strongly connected components. Two folders cannot be seen by two
     *   different user as a child for one and as an ancestor for the other.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $userId The target user id the item is added for
     * @param array $indirectlyImpactedUsersIds The users impacted by this change
     * @return void
     */
    private function detectAndRepairConflicts(
        UserAccessControl $uac,
        string $userId,
        array $indirectlyImpactedUsersIds
    ): void {
        // Detect and repair a cycle in the target user tree.
        // This can happen when a personal folder is involved.
        $this->detectAndRepairCycleInUserTree($userId);

        // Detect an repair strongly connected components between the users affected by a change other users.
        $this->detectAndRepairStronglyConnectedComponents($uac, $userId, $indirectlyImpactedUsersIds);
    }

    /**
     * Look for a cycle in the target user tree and fix it.
     *
     * @param string $userId The target user id the item is added for
     * @return void
     */
    private function detectAndRepairCycleInUserTree(string $userId)
    {
        $scc = $this->folderRelationsDetectStronglyConnectedComponents->detectInUserTree($userId);
        if (empty($scc)) {
            return;
        }

        $folderRelationToBreak = $this->identifyCycleFolderRelationToBreak($userId, $scc);
        $this->foldersRelationsTable->moveItemFrom(
            $folderRelationToBreak['foreign_id'],
            [$folderRelationToBreak['folder_parent_id']],
            FoldersRelation::ROOT
        );

        // Continue to look and fix cycle until the tree is cycle free.
        $this->detectAndRepairCycleInUserTree($userId);
    }

    /**
     * Identify which relation should be broken to solve the cycle.
     *
     * @param string $userId The target user id the item is added for
     * @param array $foldersRelations The list of folders relations responsible of a cycle
     * @return array
     */
    private function identifyCycleFolderRelationToBreak(string $userId, array $foldersRelations)
    {
        $personalFolderRelation = null;

        // Look for a personal folder. A cycle cannot be created in a user tree if it doesn't involve a personal folder.
        foreach ($foldersRelations as $folderRelation) {
            $isPersonal = $this->foldersRelationsTable->isItemPersonal($folderRelation['folder_parent_id']);
            if ($isPersonal) {
                $personalFolderRelation = $folderRelation;
                break;
            }
        }

        // If a cycle is found but it does not include a personal folder, then we have an integrity issue with the graph.
        // The cleanup task should identify and solve this issue.
        if (is_null($personalFolderRelation)) {
            $msg = __("Strongly connected components found in the tree of ({0}), but it is not related to a personal folder.", $userId); // phpcs:ignore
            throw new InternalErrorException($msg);
        }

        return $personalFolderRelation;
    }

    /**
     * Look for strongly connected components between the users impacted by a change and other passbolt users.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $userId The target user id the item is added for
     * @param array $usersIdsImpacted Users impacted by the change
     * @return void
     */
    private function detectAndRepairStronglyConnectedComponents(
        UserAccessControl $uac,
        string $userId,
        array $usersIdsImpacted
    ): void {
        $usersIdsToDetectSCCsFor = array_merge([$userId], $usersIdsImpacted);
        $scc = $this->folderRelationsDetectStronglyConnectedComponents->bulkDetectForUsers($usersIdsToDetectSCCsFor);
        if (empty($scc)) {
            return;
        }

        $this->foldersRelationsRepairStronglyConnectedComponents->repair($uac, $userId, $scc);

        // Run the repair function again in order to find others SCCs.
        $this->detectAndRepairStronglyConnectedComponents($uac, $userId, $usersIdsImpacted);
    }

    /**
     * Reconstruct a newly added item child in the target user tree.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignId The item id
     * @param string $userId The target user id the item is added for
     * @param array $change The change
     * @return void
     */
    private function reconstructChild(UserAccessControl $uac, string $foreignId, string $userId, array $change)
    {
        // If no one see the proposed child into the newly added item, then this change is not valid anymore.
        // It happens when the proposed child has been invalided by a previous change with a higher priority.
        $exists = $this->foldersRelationsTable
            ->exists(['foreign_id' => $change['foreignId'], 'folder_parent_id' => $foreignId]);
        if (!$exists) {
            return;
        }

        // Move the proposed child item into the newly added item for all users seeing the proposed child and the newly
        // added item.
        $usersIdsHavingAccessToItemAndProposedChild = $this->foldersRelationsTable
            ->getUsersIdsHavingAccessToMultipleItems([$foreignId, $change['foreignId']]);
        $this->foldersRelationsTable
            ->moveItemFor($change['foreignId'], $usersIdsHavingAccessToItemAndProposedChild, $foreignId);

        // Detect and fix conflicts.
        $this->detectAndRepairConflicts($uac, $userId, $usersIdsHavingAccessToItemAndProposedChild);
    }
}
