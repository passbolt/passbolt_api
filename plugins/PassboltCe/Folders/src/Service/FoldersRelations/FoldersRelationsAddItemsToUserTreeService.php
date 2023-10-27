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

use App\Model\Table\PermissionsTable;
use App\Utility\UserAccessControl;
use Cake\Database\Expression\TupleComparison;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Collection\FolderRelationDtoCollection;
use Passbolt\Folders\Model\Entity\FoldersRelation;

class FoldersRelationsAddItemsToUserTreeService
{
    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $permissionsTables;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsSortService
     */
    private $foldersRelationsSortService;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsCreateService
     */
    private $foldersRelationsCreateService;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsDetectStronglyConnectedComponentsService
     */
    private $folderRelationsDetectSCCsService;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsRepairStronglyConnectedComponentsService
     */
    private $foldersRelationsRepairSCCsService;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsHaveAndAreChildrenService
     */
    private FoldersRelationsHaveAndAreChildrenService $foldersRelationsHaveOrAreChildrenService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $this->permissionsTables = TableRegistry::getTableLocator()->get('Permissions');
        $this->foldersRelationsSortService = new FoldersRelationsSortService();
        $this->foldersRelationsCreateService = new FoldersRelationsCreateService();
        $this->folderRelationsDetectSCCsService = new FoldersRelationsDetectStronglyConnectedComponentsService();
        $this->foldersRelationsRepairSCCsService = new FoldersRelationsRepairStronglyConnectedComponentsService();
        $this->foldersRelationsHaveOrAreChildrenService = new FoldersRelationsHaveAndAreChildrenService();
    }

    /**
     * Add items to a user tree.
     *
     * This function doesn't check:
     * - The existence of target users
     * - The existence of the items to add to the target users trees
     * - The right for the target users to access the items
     * - The presence of the items in the users trees
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $userId The target user id the items are added for
     * @param array<\Passbolt\Folders\Model\Dto\FolderRelationDto> $items The collection of folder relation to add to the user tree.
     * @return void
     * @throws \Exception If an unexpected error occurred
     */
    public function addItemsToUserTree(UserAccessControl $uac, string $userId, array $items): void
    {
        $collection = new FolderRelationDtoCollection($items);
        $this->insertItemsInUserRootTree($userId, $collection);
        $foldersRelationsChanges = $this->getFoldersRelationsChanges($uac, $userId, $collection);

        if (empty($foldersRelationsChanges)) {
            return;
        }

        $this->applyFoldersRelationsChanges($userId, $foldersRelationsChanges);

        // If no folder were added to the user tree, there is no need to perform a cycle check.
        if (!$collection->containsFolder()) {
            return;
        }

        $foldersIds = $collection->getFoldersIds();

        // If none of the folders is a parent and also a child, the newly added folder cannot introduce a cycle.
        $shouldCheckScc = $this->foldersRelationsHaveOrAreChildrenService->haveAndAreChildren($foldersIds);
        if (!$shouldCheckScc) {
            return;
        }

        $this->detectAndRepairSCCs($uac, $userId);

        // If none of the folders is a parent and also a child in the user tree, the newly added folder cannot introduce a cycle.
        $shouldCheckSccInUserTree = $this->foldersRelationsHaveOrAreChildrenService->haveAndAreChildren(
            $foldersIds,
            $userId
        );
        if (!$shouldCheckSccInUserTree) {
            return;
        }

        $this->detectAndRepairSCCsInUserTree($userId);
    }

    /**
     * Get the list of folders relations changes to apply to the user tree in order to support the insertion of a
     * list of given items (resources and folders).
     *
     * The list of folders relations changes will be sorted as following (on top the changes to apply in priority):
     * 1. The folder relation presence in the operator tree. Priority to the operator view.
     * 2. The folder relation usage. Priority to the more used.
     * 3. The folder relation age. Priority to the oldest folder relation.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $userId The target user id the items are added for
     * @param \Passbolt\Folders\Model\Collection\FolderRelationDtoCollection $items The list of items to add to the tree
     * @return array
     */
    private function getFoldersRelationsChanges(
        UserAccessControl $uac,
        string $userId,
        FolderRelationDtoCollection $items
    ): array {
        $parentFoldersRelationsChanges = $this->getParentFoldersRelationsChanges($userId, $items);
        $childrenFoldersRelationsChanges = $this->getChildrenFoldersRelationsChanges(
            $userId,
            $items,
            $parentFoldersRelationsChanges
        );
        $foldersRelationChanges = array_merge($parentFoldersRelationsChanges, $childrenFoldersRelationsChanges);
        $this->foldersRelationsSortService->sort($foldersRelationChanges, $uac);

        return $foldersRelationChanges;
    }

    /**
     * Returns a list of folders relations which could represent a potential parent relationship for the list of
     * items added to the user tree.
     *
     * @param string $userId The target user id the items are added for
     * @param \Passbolt\Folders\Model\Collection\FolderRelationDtoCollection $items The items to look for potential parents
     * @return array<\Passbolt\Folders\Model\Entity\FoldersRelation>
     */
    private function getParentFoldersRelationsChanges(string $userId, FolderRelationDtoCollection $items): array
    {
        // R = The folders relations which could represent a potential parent relationship for the list of items added
        //     to the user tree.
        //
        // Details :
        // USERS_FOLDERS = The folders the target user can see
        // POTENTIAL_PARENTS = The parents of the newly added items in all the users trees
        // R = ITEMS_POTENTIAL_PARENTS ⋂ USERS_FOLDERS

        $foreignIds = $items->map([FolderRelationDtoCollection::class, 'mapForeignId'])->toArray();
        if (empty($foreignIds)) {
            return [];
        }

        // USERS_FOLDERS
        $userFolders = $this->permissionsTables->findAllByAro(
            PermissionsTable::FOLDER_ACO,
            $userId,
            ['checkGroupsUsers' => true]
        )
            ->select('aco_foreign_key');

        // POTENTIAL_PARENTS
        $query = $this->foldersRelationsTable->find()
            ->where(['foreign_id IN' => $foreignIds]);

        // R = POTENTIAL_PARENTS ⋂ USERS_FOLDERS
        $query->where(['folder_parent_id IN' => $userFolders]);

        return $query->select(['foreign_id', 'folder_parent_id'])
            ->group(['foreign_id', 'folder_parent_id'])
            ->all()
            ->toArray();
    }

    /**
     * Returns a list of folders relations which could represent a potential child relationship for the list of folders
     * added to the user tree.
     *
     * @param string $userId The target user id the items are added for
     * @param \Passbolt\Folders\Model\Collection\FolderRelationDtoCollection $items The items to look for potential parents
     * @param array $excludeFoldersRelations The folders relations to exclude
     * @return array<\Passbolt\Folders\Model\Entity\FoldersRelation>
     */
    private function getChildrenFoldersRelationsChanges(
        string $userId,
        FolderRelationDtoCollection $items,
        array $excludeFoldersRelations
    ): array {
        // R = The folders relations which could represent a potential child relationship for the list of folders added
        //     to the user tree.
        //
        // Details :
        // USERS_ITEMS = The items (folders or resources) the target user can see
        // POTENTIAL_CHILDREN = The children of the newly added items in all the users trees
        // R = POTENTIAL_CHILDREN ⋂ USERS_ITEMS

        // CHILDREN
        $foreignIds = $items->getFoldersIds();

        if (empty($foreignIds)) {
            return [];
        }

        // POTENTIAL_CHILDREN
        $query = $this->foldersRelationsTable->find();
        $query->where(['folder_parent_id IN' => $foreignIds]);

        // R = POTENTIAL_CHILDREN ⋂ USERS_ITEMS
        $userItems = $this->foldersRelationsTable->findByUserId($userId);
        $query->where(['foreign_id IN' => $userItems->select('foreign_id')]);

        $foldersRelations = $query->select(['foreign_id', 'folder_parent_id'])
            ->group(['foreign_id', 'folder_parent_id'])
            ->all()
            ->toArray();

        // Excluding the folders relations from the SQL query cost more than performing the operation in PHP at the moment
        // this code was written.
        if (!empty($excludeFoldersRelations)) {
            $excludeRelationMap = [];
            foreach ($excludeFoldersRelations as $excludeFoldersRelation) {
                $excludeRelationMap[$excludeFoldersRelation->foreign_id][] = $excludeFoldersRelation->folder_parent_id;
            }
            $foldersRelations = array_filter($foldersRelations, function ($folderRelation) use ($excludeRelationMap) {
                return !isset($excludeRelationMap[$folderRelation->foreign_id])
                   || !in_array($folderRelation->folder_parent_id, $excludeRelationMap[$folderRelation->foreign_id]);
            });
        }

        return $foldersRelations;
    }

    /**
     * Build a folders relations IN or NOT IN tuple comparison used in query where clause.
     * Output SQL like:
     * WHERE (foreign_id, folder_parent_id) IN ((FOLDER_RELATION_1_FOREIGN_ID, FOLDER_RELATION_1_FOLDER_PARENT_ID), ...)
     *
     * @param array<\Passbolt\Folders\Model\Entity\FoldersRelation> $foldersRelations The folders relations to build a tuple comparison expression for
     * @param bool $isInOperator (Optional) By default true and the expression with use the IN operator. If false the
     * expression will use the NOT IN operator.
     * @return \Cake\Database\Expression\TupleComparison
     */
    private function buildFoldersRelationsTupleComparisonExpression(
        array $foldersRelations,
        bool $isInOperator = true
    ): TupleComparison {
        $operator = $isInOperator ? 'IN' : 'NOT IN';
        $excludeFoldersRelationsArray = array_map(function (FoldersRelation $excludeFolderRelation) {
            return $excludeFolderRelation->extract(['foreign_id', 'folder_parent_id']);
        }, $foldersRelations);

        return new TupleComparison(['foreign_id', 'folder_parent_id'], $excludeFoldersRelationsArray, [], $operator);
    }

    /**
     * Insert the items at the root of the user's tree.
     *
     * @param string $userId The target user id the items are added for
     * @param \Passbolt\Folders\Model\Collection\FolderRelationDtoCollection $items The list of items to add to the tree
     * @return void
     * @throws \Exception If a folder relation cannot be created
     */
    private function insertItemsInUserRootTree(string $userId, FolderRelationDtoCollection $items)
    {
        foreach ($items as $item) {
            $folderRelationToCreate = $item->clone();
            $folderRelationToCreate->userId = $userId;
            $this->foldersRelationsCreateService->create($folderRelationToCreate->toArray(), false);
        }
    }

    /**
     * Apply the folders relations changes to the user tree.
     *
     * @param string $userId The target user id the items are added for
     * @param array<\Passbolt\Folders\Model\Entity\FoldersRelation> $foldersRelationsChanges The folders relations changes
     * @return void
     */
    private function applyFoldersRelationsChanges(string $userId, array $foldersRelationsChanges): void
    {
        $changesToApply = [];
        $changesToCancel = [];

        /*
         * Sort out the changes to apply and the changes to cancel. Only one folder relation change for a given item
         * defined by its foreign_id can be played, other changes for the same item will be cancelled.
         * Regroup the folders relations changes to apply by folder parent. This will improve the performance by
         * reducing the number of SQL queries made.
         */
        $appliedChangesHash = [];
        foreach ($foldersRelationsChanges as $folderRelationChange) {
            if (isset($appliedChangesHash[$folderRelationChange->foreign_id])) {
                $changesToCancel[] = $folderRelationChange;
                continue;
            }
            $appliedChangesHash[$folderRelationChange->foreign_id] = true;
            $changesToApply[$folderRelationChange->folder_parent_id][] = $folderRelationChange->foreign_id;
        }

        // Move to the root the items for which the folders relations changes got cancelled.
        if (!empty($changesToCancel)) {
            $this->foldersRelationsTable->updateAll([
                'folder_parent_id' => FoldersRelation::ROOT,
            ], $this->buildFoldersRelationsTupleComparisonExpression($changesToCancel));
        }

        // Apply the changes to the user tree.
        foreach ($changesToApply as $folderParentId => $foreignIds) {
            $this->foldersRelationsTable->updateAll(
                ['folder_parent_id' => $folderParentId],
                ['user_id' => $userId, 'foreign_id IN' => $foreignIds]
            );
        }
    }

    /**
     * Detect and repair strongly connected components created between the users impacted by the changes and the users
     * having a tree intersecting the previous ones.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the action
     * @param string $userId The target user id the items are added for
     * @return void
     * @throw InternalErrorException If a SCC is found but cannot be repaired.
     */
    private function detectAndRepairSCCs(UserAccessControl $uac, string $userId): void
    {
        $foldersRelationsScc = $this->folderRelationsDetectSCCsService->detectFirstInSharedFolders();

        if (!empty($foldersRelationsScc)) {
            $brokenFolderRelation = $this->foldersRelationsRepairSCCsService->repair(
                $uac,
                $userId,
                $foldersRelationsScc
            );

            if (is_null($brokenFolderRelation)) {
                $msg = "Strongly connected components found, but cannot be repaired."; // phpcs:ignore
                throw new InternalErrorException($msg);
            }
            $this->detectAndRepairSCCs($uac, $userId);
        }
    }

    /**
     * Detect and repair strongly connected components created in the target user tree impacted by the changes and
     * involving a target user personal folder.
     *
     * @param string $userId The target user id the items are added for
     * @return void
     * @throw InternalErrorException If a SCC is found but is not relative to a user personal folder.
     */
    private function detectAndRepairSCCsInUserTree(string $userId): void
    {
        $foldersRelationsScc = $this->folderRelationsDetectSCCsService->detectInUserTree($userId);

        if (!empty($foldersRelationsScc)) {
            $brokenFolderRelation = $this->foldersRelationsRepairSCCsService->repairPersonal($foldersRelationsScc);

            // If a cycle is found, but it does not include a personal folder, then we have an integrity issue with the
            // graph. The cleanup task should identify and solve this issue.
            if (!$brokenFolderRelation) {
                $msg = "Strongly connected components found in the tree of ($userId), but it is not related to a personal folder."; // phpcs:ignore
                throw new InternalErrorException($msg);
            }
            $this->detectAndRepairSCCsInUserTree($userId);
        }
    }
}
