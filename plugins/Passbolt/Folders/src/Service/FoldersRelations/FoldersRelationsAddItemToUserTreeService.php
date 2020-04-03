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
 * @since         2.14.0
 */

namespace Passbolt\Folders\Service\FoldersRelations;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Exception;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;

class FoldersRelationsAddItemToUserTreeService
{
    /**
     * @var FoldersItemsGetAncestorsService
     */
    private $foldersItemsGetAncestorsService;

    /**
     * @var FoldersItemsHasAncestorService
     */
    private $foldersItemsHasAncestorService;

    /**
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var FoldersRelationsCreateService
     */
    private $foldersRelationsCreateService;

    /**
     * @var UserHasPermissionService
     */
    private $userHasPermissionService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersItemsGetAncestorsService = new FoldersItemsGetAncestorsService();
        $this->foldersItemsHasAncestorService = new FoldersItemsHasAncestorService();
        $this->foldersRelationsCreateService = new FoldersRelationsCreateService();
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     * Add an item to a user folders tree.
     *
     * @param UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The entity model
     * @param string $foreignId The entity id
     * @param string $userId The target user id
     * @param bool (optional) $isPersonal (Optional) Is the item personal. Default false.
     * @return void
     * @throws Exception If an unexpected error occurred
     */
    public function addItemToUserTree(UserAccessControl $uac, string $foreignModel, string $foreignId, string $userId, bool $isPersonal = false)
    {
        $existsInUserTree = $this->foldersRelationsTable->existsInUserTree($userId, $foreignId);
        if ($existsInUserTree) {
            return;
        }

        $this->foldersRelationsTable->getConnection()->transactional(function () use (&$uac, $foreignModel, $foreignId, $userId, $isPersonal) {
            // Insert the item at the root of the target user tree.
            $this->foldersRelationsCreateService->create($uac, $foreignModel, $foreignId, $userId, null);

            // When an existing item is shared with a user, the user tree will be reconstructed based on other trees in
            // order to ensure a common organization among all passbolt users.
            // A highest priority is given to the operator representation, a first treatment is done to reconstruct only
            // the operator nodes in the target user tree. Then a second treatment is done to reconstruct nodes from
            // other users trees.
            list ($appliedFolderParentId, $appliedFolderChildrenIds) = $this->reconstructUserTreeBasedOnOperatorTree($uac, $foreignModel, $foreignId, $userId, $isPersonal);
            $this->reconstructUserTreeBasedOnOtherUsersTrees($uac, $foreignModel, $foreignId, $userId, $appliedFolderParentId, $appliedFolderChildrenIds);
        });
    }

    /**
     * Reconstruct the user tree based on the operator representation.
     *
     * @param UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The entity model
     * @param string $foreignId The entity id
     * @param string $userId The target user
     * @param bool (optional) $isPersonal (Optional) Is the item personal. Default false.
     * @return array
     * [
     *   0 => The applied folder parent id if any
     *   1 => The applied folder children ids if any
     * ]
     */
    private function reconstructUserTreeBasedOnOperatorTree(UserAccessControl $uac, string $foreignModel, string $foreignId, string $userId, bool $isPersonal = false)
    {
        $appliedOperatorFolderParentId = $this->reconstructFolderParentBasedOnOperatorTree($uac, $foreignModel, $foreignId, $userId);
        $appliedOperatorFolderChildrenIds = [];

        // If the item is a folder, reconstruct the folder children.
        if ($foreignModel === FoldersRelation::FOREIGN_MODEL_FOLDER) {
            $appliedOperatorFolderChildrenIds = $this->reconstructFolderChildrenFromOperatorTree($uac, $foreignId, $userId, $isPersonal);
        }

        return [$appliedOperatorFolderParentId, $appliedOperatorFolderChildrenIds];
    }

    /**
     * Reconstruct a folder parent in a user tree based on the operator representation.
     *
     * @param UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The target entity model
     * @param string $foreignId The target entity
     * @param string $userId The target user
     * @return string|null
     */
    private function reconstructFolderParentBasedOnOperatorTree(UserAccessControl $uac, string $foreignModel, string $foreignId, string $userId)
    {
        $folderParentId = $this->foldersRelationsTable->findByForeignIdAndUserId($foreignId, $uac->userId())
            ->select('folder_parent_id')
            ->extract('folder_parent_id')
            ->first();

        // The item doesn't have any parent in the operator tree.
        if (is_null($folderParentId)) {
            return null;
        }

        // The user cannot see the operator folder parent.
        $existsInUserTree = $this->foldersRelationsTable->existsInUserTree($userId, $folderParentId);
        if (!$existsInUserTree) {
            return null;
        }

        // Conflict detection: Another item in the target user tree could be a parent of the target item.
        // The operator representation has the highest priority and it will be applied: the target item will be moved
        // from the identified other parent folders to the root of the users having this representation.
        $conflictedFolderParentsIds = $this->getPotentialFolderParentsIds($foreignId, $userId, ['exclude' => $folderParentId]);
        if (!empty($conflictedFolderParentsIds)) {
            $this->foldersRelationsTable->moveItemFrom($foreignId, $conflictedFolderParentsIds);
        }

        // If the item is a folder, try to detect a cycle.
        if ($foreignModel === FoldersRelation::FOREIGN_MODEL_FOLDER) {
            // Cycle detection: An ancestor of the target folder in the target user tree is a child of the target folder
            // in another user tree. Then break the relation between the identified child and the target folder to solve
            // the cycle.
            $cycleFolderIds = $this->detectCycleBeforeReconstructFolderParent($userId, $foreignId, $folderParentId);
            foreach ($cycleFolderIds as $cycleFolderId) {
                $this->foldersRelationsTable->moveItemFrom($cycleFolderId, [$foreignId]);
            }
        }

        // Move the item into the parent folder.
        $this->foldersRelationsTable->moveItemFor($foreignId, [$userId], $folderParentId);

        return $folderParentId;
    }

    /**
     * Get the potential folder parents of a folder in a user tree.
     * @param string $folderId The target folder
     * @param string $userId The target user
     * @param array $options Option
     * [
     *   array $exclude Exclude folders from the query
     * ]
     * @return array
     */
    private function getPotentialFolderParentsIds(string $folderId, string $userId, array $options = [])
    {
        $userItems = $this->foldersRelationsTable->findByUserIdAndForeignModel($userId, FoldersRelation::FOREIGN_MODEL_FOLDER);

        $query = $this->foldersRelationsTable->find()
            ->where([
                'foreign_id' => $folderId,
                'folder_parent_id IN' => $userItems->select('foreign_id'),
            ]);

        $exclude = Hash::get($options, 'exclude', []);
        if (!empty($exclude)) {
            $query->where([
                'folder_parent_id NOT IN' => $exclude,
            ]);
        }

        return $query->select('folder_parent_id')
            ->distinct()
            ->extract('folder_parent_id')
            ->toArray();
    }

    /**
     * Detect a cycle before reconstructing an item parent.
     *
     * A cycle is detected when an ancestor of the target folder in the target user tree is a child of the target folder
     * in another user tree. Then break the relation between the identified child and the target folder.
     *
     * @param string $userId The target user tree
     * @param string $folderId The target folder that will have be moved in the parent
     * @param string $folderParentId The target folder parent the folder will be moved in
     * @return array A list of folder which are responsible of a cycle
     */
    private function detectCycleBeforeReconstructFolderParent(string $userId, string $folderId, string $folderParentId)
    {
        $ancestorsIds = $this->foldersItemsGetAncestorsService->getAncestors($userId, $folderParentId);
        $potentialChildrenIds = $this->getPotentialFolderChildrenIds($folderId, $userId);

        return array_intersect($ancestorsIds, $potentialChildrenIds);
    }

    /**
     * Get the list of potential children of a folder in a user tree.
     * @param string $folderId The target folder
     * @param string $userId The target user
     * @return array The list of potential children
     */
    private function getPotentialFolderChildrenIds(string $folderId, string $userId)
    {
        // R = All the items that are children of the given folder in all users tree that the target user can also see.
        //
        // Details :
        // USERS_ITEMS = All the items the target user can see
        // FOLDER_CHILDREN = The children if the target folder in all users trees
        // R = USERS_ITEMS ⋂ FOLDER_CHILDREN

        // USERS_ITEMS
        $userItemsQuery = $this->foldersRelationsTable->findByUserIdAndForeignModel($userId, PermissionsTable::FOLDER_ACO);

        // FOLDER_CHILDREN
        $childrenQuery = $this->foldersRelationsTable->findByFolderParentId($folderId);

        // R = USERS_ITEMS ⋂ FOLDER_CHILDREN
        $resultQuery = $childrenQuery->where(['foreign_id IN' => $userItemsQuery->select('foreign_id')]);

        return $resultQuery->select('foreign_id')
            ->distinct()
            ->extract('foreign_id')
            ->toArray();
    }

    /**
     * Reconstruct the children of a folder in a user tree based on the operator representation
     * @param UserAccessControl $uac The operator
     * @param string $folderId The target folder
     * @param string $userId The target user tree
     * @param bool $isPersonal (Optional) Is the permission added to a personal folder. Default false.
     * @return array The list of children that has been added to the folder
     */
    private function reconstructFolderChildrenFromOperatorTree(UserAccessControl $uac, string $folderId, string $userId, bool $isPersonal = false)
    {
        $childrenIds = $this->getPotentialFolderChildrenIdsFromUserTree($uac->userId(), $folderId, $userId);

        foreach ($childrenIds as $childId) {
            // Self organization detection: If one of the operator child is self organized for the operator, then move
            // this child to the root of the operator and don't consider it as a child to reconstruct into the target
            // folder for the target user.
            if ($isPersonal) {
                $canUpdate = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $childId, $uac->userId(), Permission::UPDATE);
                if (!$canUpdate) {
                    $this->foldersRelationsTable->moveItemFor($childId, [$uac->userId()], null);
                    continue;
                }
            }

            // Conflict detection: If one of the operator children is organized in another folder the target user can see.
            // Then operator view is applied and other representations are broken. The child in conflict will be moved at
            // the root of the users having a different representations.
            $conflictedFolderParentIds = $this->getPotentialFolderParentsIds($childId, $userId);
            array_splice($conflictedFolderParentIds, array_search($folderId, $conflictedFolderParentIds), 1);
            if (!empty($conflictedFolderParentIds)) {
                $this->foldersRelationsTable->moveItemFrom($childId, $conflictedFolderParentIds, null);
            }

            // Cycle detection: The identified child or one of its children in the target user tree is an ancestor of
            // the target folder in another user tree.
            $cyclesFoldersIds = $this->detectCycleBeforeReconstructFolderChildren($userId, $childId, $folderId);
            // As the operator representation has the highest priority: break the cycle by moving the target folder from
            // the folder responsible of a cycle to the root of the users trees having this representation.
            foreach ($cyclesFoldersIds as $cyclesFolderId) {
                $this->foldersRelationsTable->moveItemFrom($folderId, [$cyclesFolderId], null);
            }

            $this->foldersRelationsTable->moveItemFor($childId, [$userId], $folderId);
        }

        return $childrenIds;
    }

    /**
     * Get the list of potential children of a folder for a user based on another user representation.
     *
     * @param string $fromUserIdTree The base user tree
     * @param string $folderId The target folder
     * @param string $userId The target user
     * @return array The list of potential children ids
     */
    private function getPotentialFolderChildrenIdsFromUserTree(string $fromUserIdTree, string $folderId, string $userId)
    {
        // R = All the items that are children of the given folder in the based user representation the target user can
        //     also see.
        //
        // Details :
        // USERS_ITEMS = All the items the target user can see
        // FOLDER_CHILDREN_FROM_BASED_USER_TREE = The children if the target folder in the based user tree
        // R = USERS_ITEMS ⋂ FOLDER_CHILDREN_FROM_BASED_USER_TREE

        // USERS_ITEMS
        $userItemsQuery = $this->foldersRelationsTable->findByUserIdAndForeignModel($userId, PermissionsTable::FOLDER_ACO);

        // FOLDER_CHILDREN_FROM_BASED_USER_TREE
        $childrenFromBasedUserTreeQuery = $this->foldersRelationsTable->find()
            ->where([
                'folder_parent_id' => $folderId,
                'user_id' => $fromUserIdTree,
            ]);

        // R = USERS_ITEMS ⋂ FOLDER_CHILDREN_FROM_BASED_USER_TREE
        $result = $childrenFromBasedUserTreeQuery->where([
            'foreign_id IN' => $userItemsQuery->select('foreign_id'),
        ]);

        return $result->select('foreign_id')
            ->distinct()
            ->extract('foreign_id')
            ->toArray();
    }

    /**
     * Detect a cycle before reconstructing an item children.
     *
     * A cycle is detected when a potential child or one of its children is parent of the target folder in another
     * user's tree.
     *
     * @param string $userId The target user tree
     * @param string $folderId The target folder that will have be moved in the parent
     * @param string $folderParentId The target folder parent the folder will be moved in
     * @return array A list of folder which are responsible of a cycle
     */
    private function detectCycleBeforeReconstructFolderChildren(string $userId, string $folderId, string $folderParentId)
    {
        // R = All the items that could be a child and parent of the target folder after reconstruction.
        //
        // Details :
        // POTENTIAL_CHILDREN_AND_GRAND_CHILDREN = The potential child and all its descendants in the target user tree
        // FOLDER_ANCESTORS = The folders ancestors in all users trees
        // R = POTENTIAL_CHILDREN_AND_GRAND_CHILDREN ⋂ FOLDER_ANCESTORS

        $conflictedFoldersIds = [];

        $usersIdsSeeingFolderParent = $this->getUsersIdsHavingAccessTo($folderParentId);
        $children = $this->getFolderChildrenIdsFromUserTree($userId, $folderId, true);
        $children[] = $folderId;
        foreach ($usersIdsSeeingFolderParent as $userIdSeeingFolderParent) {
            foreach ($children as $child) {
                $cycleDetected = $this->foldersItemsHasAncestorService->hasAncestor($folderParentId, $child, $userIdSeeingFolderParent);
                if ($cycleDetected) {
                    $conflictedFoldersIds[] = $child;
                }
            }
        }

        return $conflictedFoldersIds;
    }

    /**
     * Get a list of users ids having access to a given item.
     * @param string $foreignId The target folder
     * @return array
     */
    public function getUsersIdsHavingAccessTo(string $foreignId)
    {
        return $this->foldersRelationsTable->findByForeignId($foreignId)
            ->select('user_id')
            ->extract('user_id')
            ->toArray();
    }

    /**
     * Get folder's children ids in a user tree.
     *
     * @param string $userId The target user
     * @param string $folderId The target folder
     * @param bool $recursive (Optional) Return the children of the children recursively. Note the result will be a flat
     * of children, the structure won't be preserved.
     * @return array
     */
    public function getFolderChildrenIdsFromUserTree(string $userId, string $folderId, bool $recursive = false)
    {
        $childrenIds = $this->foldersRelationsTable->find()
            ->where([
                'user_id' => $userId,
                'folder_parent_id' => $folderId,
            ])
            ->select('foreign_id')
            ->extract('foreign_id')
            ->toArray();

        if ($recursive) {
            foreach ($childrenIds as $childId) {
                $childrenIds += $this->getFolderChildrenIdsFromUserTree($userId, $childId, true);
            }
        }

        return $childrenIds;
    }

    /**
     * Reconstruct the user tree based on the other users trees.
     *
     * @param UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The target entity model
     * @param string $foreignId The target entity
     * @param string $userId The target user
     * @param string|null $appliedOperatorFolderParentId The applied operator folder parent id. Null if none.
     * @param array $appliedFolderChildrenId The already applied operator folder children if any.
     * @return void
     */
    private function reconstructUserTreeBasedOnOtherUsersTrees(UserAccessControl $uac, string $foreignModel, string $foreignId, string $userId, string $appliedOperatorFolderParentId = null, array $appliedFolderChildrenId = [])
    {
        // If the operator view has not been applied, or the operator doesn't see the item into a folder. Try to
        // apply other users representations.
        if (is_null($appliedOperatorFolderParentId)) {
            $this->reconstructFolderParentFromOtherUsersTrees($uac, $foreignModel, $foreignId, $userId);
        }

        // If the item is a folder, reconstruct the folder children.
        if ($foreignModel === FoldersRelation::FOREIGN_MODEL_FOLDER) {
            $this->reconstructFolderChildrenFromOtherUsersTrees($uac, $foreignId, $userId, $appliedFolderChildrenId);
        }
    }

    /**
     * Reconstruct the parent of a folder in a user tree based on non operator users representation
     * @param UserAccessControl $uac The operator
     * @param string $foreignModel The target entity model
     * @param string $foreignId The target entity
     * @param string $userId The target user
     * @return void
     */
    private function reconstructFolderParentFromOtherUsersTrees(UserAccessControl $uac, string $foreignModel, string $foreignId, string $userId)
    {
        $folderParentsIds = $this->getPotentialFolderParentsIds($foreignId, $userId);
        $folderParentsIdsCount = count($folderParentsIds);

        if (!$folderParentsIdsCount) {
            return;
        } elseif ($folderParentsIdsCount > 1) {
            // Conflict detection: Multiple folder parents found.
            // We don't choose a version over another, the folder will be moved from the identified other parent folders
            // to the root of users having this representation.
            $this->foldersRelationsTable->moveItemFrom($foreignId, $folderParentsIds, null);

            return;
        } else {
            $folderParentId = $folderParentsIds[0];
        }

        // If the item is a folder, try to detect a cycle.
        if ($foreignModel === FoldersRelation::FOREIGN_MODEL_FOLDER) {
            // Cycle detection: An ancestor of the target folder in the target user tree is a child of the target folder in
            // another user tree.
            $cyclesFoldersIds = $this->detectCycleBeforeReconstructFolderParent($userId, $foreignId, $folderParentId);
            if (!empty($cyclesFoldersIds)) {
                // If a cycle is detected in the operator tree then:
                // - Do not apply the parent change;
                // - Break the parent folder relationship in other trees: Move the folder to the root of users seeing the
                //   the target folder in the parent folder.
                $cyclesFoldersIdsInOperatorTree = $this->filterFoldersIdsByUserIdAccess($userId, $cyclesFoldersIds);
                if (!empty($cyclesFoldersIdsInOperatorTree)) {
                    $this->foldersRelationsTable->moveItemFrom($foreignId, [$folderParentId]);

                    return;
                }

                // If the cycle are relative to other users tree then: Move the detected cycle folder to the root for
                // each user seeing it in the target folder.
                foreach ($cyclesFoldersIds as $cyclesFolderId) {
                    $this->foldersRelationsTable->moveItemFrom($cyclesFolderId, [$foreignId], null);
                }
            }
        }

        // Move the folder into the parent parent.
        $this->foldersRelationsTable->moveItemFor($foreignId, [$userId], $folderParentId);
    }

    /**
     * Filter a list of items ids and return the ones that are in a user tree.
     * @param string $userId The target user
     * @param array $foldersIds The list of items id to filter
     * @return array The filtered list
     */
    private function filterFoldersIdsByUserIdAccess(string $userId, array $foldersIds = [])
    {
        if (empty($foldersIds)) {
            return [];
        }

        return $this->foldersRelationsTable->find()
            ->where([
                'foreign_id IN' => $foldersIds,
                'user_id' => $userId,
            ])
            ->select('foreign_id')
            ->extract('foreign_id')
            ->toArray();
    }

    /**
     * Reconstruct the children of a folder in a user tree based on non operator users representation
     * @param UserAccessControl $uac The operator
     * @param string $folderId The target folder
     * @param string $userId The target user
     * @param array $operatorFolderChildrenIds A list of items already added by the operator reconstruction process.
     * @return void
     */
    private function reconstructFolderChildrenFromOtherUsersTrees(UserAccessControl $uac, string $folderId, string $userId, array $operatorFolderChildrenIds = [])
    {
        $childrenIds = $this->getPotentialFolderChildrenIds($folderId, $userId);
        $childrenIds = array_diff($childrenIds, $operatorFolderChildrenIds);

        foreach ($childrenIds as $childId) {
            // Cycle detection: The identified child or one of its children in the target user tree is an ancestor of
            // the target folder in another user tree.
            $cyclesFoldersIds = $this->detectCycleBeforeReconstructFolderChildren($userId, $childId, $folderId);

            // If a cycle is detected in the operator tree then:
            // - Do not apply the child change;
            // - Break the parent folder relationship in other trees.
            $cyclesFoldersIdsInOperatorTree = $this->filterFoldersIdsByUserIdAccess($uac->userId(), $cyclesFoldersIds);
            if (!empty($cyclesFoldersIdsInOperatorTree)) {
                $this->foldersRelationsTable->moveItemFrom($childId, [$folderId]);

                return;
            }

            // If the cycle are relative to other users trees then: Move the target folder from the folder responsible
            // of a cycle to the root of the users trees having this representation.
            // @todo no test to validate this line
            foreach ($cyclesFoldersIds as $cyclesFolderId) {
                $this->foldersRelationsTable->moveItemFrom($folderId, [$cyclesFolderId], null);
            }

            // Move the child into the target folder.
            $this->foldersRelationsTable->moveItemFor($childId, [$userId], $folderId);
        }
    }
}
