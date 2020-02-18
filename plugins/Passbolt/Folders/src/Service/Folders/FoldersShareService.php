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

namespace Passbolt\Folders\Service\Folders;

use App\Model\Entity\Permission;
use App\Model\Table\GroupsUsersTable;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\PermissionsCreateService;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Exception;
use Passbolt\Folders\Model\Behavior\ContainFolderParentIdBehavior;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Service\FoldersItems\FoldersItemsGetAncestorsService;
use Passbolt\Folders\Service\FoldersItems\FoldersItemsHasAncestorService;
use Passbolt\Folders\Service\FoldersRelationsHasAncestorService;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsCreateService;

class FoldersShareService
{
    use EventDispatcherTrait;

    /**
     * @var FoldersTable
     */
    private $foldersTable;

    /**
     * @var FoldersItemsGetAncestorsService
     */
    private $foldersItemsGetAncestorsService;

    /**
     * @var FoldersItemsHasAncestorService
     */
    private $foldersItemsHasAncestorService;

    /**
     * @var ResourcesMoveService
     */
    private $foldersMoveService;

    /**
     * @var FoldersRelationsCreateService
     */
    private $foldersRelationsCreateService;

    /**
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var PermissionsCreateService
     */
    private $permissionsCreateService;

    /**
     * @var PermissionsTable
     */
    private $permissionsTable;

    /**
     * @var UserHasPermissionService
     */
    private $userHasPermissionService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersRelationsCreateService = new FoldersRelationsCreateService();
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->foldersItemsGetAncestorsService = new FoldersItemsGetAncestorsService();
        $this->foldersItemsHasAncestorService = new FoldersItemsHasAncestorService();
        $this->permissionsCreateService = new PermissionsCreateService();
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     * Update a folder for the current user.
     *
     * @param UserAccessControl $uac The current user
     * @param string $id The folder to update
     * @param array $data The folder data
     * @return void|Folder
     * @throws Exception If an unexpected error occurred
     */
    public function share(UserAccessControl $uac, string $id, array $data = [])
    {
        $folder = $this->getFolder($id, $uac);
        $this->assertUserCanShare($uac, $folder);

        if (empty($data)) {
            return $folder;
        }

        $this->foldersTable->getConnection()->transactional(function () use (&$folder, $uac, $data) {
            $this->updatePermissions($uac, $folder, $data);
        });

//        $this->dispatchEvent(self::FOLDERS_UPDATE_FOLDER_EVENT, [
//            'uac' => $uac,
//            'folder' => $folder,
//        ]);

        return $folder;
    }

    /**
     * Retrieve the folder.
     *
     * @param string $folderId The folder identifier to retrieve.
     * @param UserAccessControl $uac UserAccessControl updating the resource
     * @return Folder
     * @throws NotFoundException If the folder does not exist.
     */
    private function getFolder(string $folderId, UserAccessControl $uac)
    {
        try {
            return $this->foldersTable->get($folderId, [
                'finder' => ContainFolderParentIdBehavior::FINDER_NAME,
                'user_id' => $uac->userId(),
                'contain' => ['Permissions']
            ]);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The folder does not exist.'));
        }
    }

    /**
     * Assert if the operator can share the given folder.
     * @param UserAccessControl $uac The operator
     * @param Folder $folder The folder to assert
     * @throws ForbiddenException If the user cannot share the folder
     */
    private function assertUserCanShare(UserAccessControl $uac, Folder $folder)
    {
        $userId = $uac->userId();
        $isAllowed = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folder->id, $userId, Permission::OWNER);
        if (!$isAllowed) {
            throw new ForbiddenException(__('You are not allowed to update the permissions of this folder.'));
        }
    }

    /**
     * Update the folder permission
     * @param UserAccessControl $uac The operator
     * @param Folder $folder The folder to update the permission
     * @param array $data The request data
     * @throws Exception If something went wrong
     */
    private function updatePermissions(UserAccessControl $uac, Folder $folder, array $data)
    {
        $isPersonal = $this->foldersTable->isPersonal($folder->id);
        $permissionsData = Hash::get($data, 'permissions', []);
        $keepPermissionsIds = [];

        foreach ($permissionsData as $permissionData) {
            $permissionId = Hash::get($permissionData, 'id', null);
            if (is_null($permissionId)) {
                $this->addPermission($uac, $folder, $permissionData, $isPersonal);
            } else {
                $this->updatePermission($folder, $permissionData);
                $keepPermissionsIds[] = $permissionData['id'];
            }
        }

        $originalPermissionsIds = Hash::extract($folder->permissions, '{n}.id');
        $permissionsIdsToDelete = array_diff($originalPermissionsIds, $keepPermissionsIds);
//        var_dump('delete all this permissions', $permissionsIdsToDelete);
    }

    /**
     * Add a permission to a folder.
     * @param UserAccessControl $uac The operator
     * @param Folder $folder The folder to add a permission onto
     * @param array $permissionData The permission data object
     * @param bool $isPersonal (Optional) Is the permission added to a personal folder. Default false.
     * @throws Exception
     */
    private function addPermission(UserAccessControl $uac, Folder $folder, array $permissionData, bool $isPersonal = false)
    {
        $aro = Hash::get($permissionData, 'aro', '');
        $aroForeignKey = Hash::get($permissionData, 'aro_foreign_key', '');
        $type = Hash::get($permissionData, 'type', '');

        $this->permissionsCreateService->create($uac, PermissionsTable::FOLDER_ACO, $folder->id, $aro, $aroForeignKey, $type);

        if ($aro === PermissionsTable::GROUP_ARO) {
            $this->addFolderToGroupUsersTrees($uac, $folder, $aroForeignKey, $isPersonal);
        } else {
            $this->addFolderToUserTree($uac, $folder, $aroForeignKey, $isPersonal);
        }
    }

    /**
     * Add a folder to a group of users trees.
     * @param UserAccessControl $uac The operator
     * @param Folder $folder The target folder
     * @param string $groupId The target group
     * @param bool $isPersonal (Optional) Is the permission added to a personal folder. Default false.
     * @throws Exception If something wrong occurred
     */
    private function addFolderToGroupUsersTrees(UserAccessControl $uac, Folder $folder, string $groupId, bool $isPersonal = false)
    {
        $grousUsersIds = $this->groupsUsersTable->findByGroupId($groupId)->extract('user_id')->toArray();
        foreach ($grousUsersIds as $groupUserId) {
            $this->addFolderToUserTree($uac, $folder, $groupUserId, $isPersonal);
        }
    }

    /**
     * Add a folder to a user tree.
     * @param UserAccessControl $uac The operator
     * @param Folder $folder The target folder
     * @param string $userId The target user
     * @param bool $isPersonal (Optional) Is the permission added to a personal folder. Default false.
     * @throws Exception If something wrong occurred
     */
    private function addFolderToUserTree(UserAccessControl $uac, Folder $folder, string $userId, bool $isPersonal = false)
    {
        $existsInUserTree = $this->foldersRelationsTable->existsInUserTree($userId, $folder->id);
        if ($existsInUserTree) {
            return;
        }

        // Insert the folder at the root of the target user tree.
        $this->foldersRelationsCreateService->create($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folder->id, $userId, null);

        // When an existing folder is shared with a user, the folder is added to the user tree. In order to ensure a
        // common organization among all passbolt users, the user tree will be reconstructed based on other trees.
        // A highest priority is given to the operator representation, so the algorithm will first apply the operator
        // view (if any), and then will apply other users representations (if any).
        $appliedFolderParentId = $this->reconstructFolderParentFromOperatorTree($folder, $userId);
        $operatorFolderChildren = $this->reconstructFolderChildrenFromOperatorTree($uac, $folder, $userId, $isPersonal);
        if (is_null($appliedFolderParentId)) {
            $this->reconstructFolderParentFromOtherUsersTrees($uac, $folder, $userId);
        }
        $this->reconstructFolderChildrenFromOtherUsersTrees($uac, $folder, $userId, $operatorFolderChildren);
    }

    /**
     * Reconstruct a folder parent in a user tree based on the operator representation
     * @param UserAccessControl $uac The operator
     * @param Folder $folder The target folder
     * @param string $userId The target user
     * @return string|null
     */
    private function reconstructFolderParentFromOperatorTree(Folder $folder, string $userId)
    {
        // The folder doesn't have any parent in the operator tree.
        if (is_null($folder->folder_parent_id)) {
            return null;
        }

        // The user cannot see the operator folder parent.
        $existsInUserTree = $this->foldersRelationsTable->existsInUserTree($userId, $folder->folder_parent_id);
        if (!$existsInUserTree) {
            return null;
        }

        // Conflict detection: Another folder in the target user tree could be a parent of the target folder.
        // The operator representation has the highest priority and it will be applied: the target folder will be moved
        // from the identified other parent folders to the root of the users having this representation.
        $conflictedFolderParentsIds = $this->getPotentialFolderParentsIds($folder->id, $userId, ['exclude' => $folder->folder_parent_id]);
        if (!empty($conflictedFolderParentsIds)) {
            $this->foldersRelationsTable->moveItemFrom($folder->id, $conflictedFolderParentsIds);
        }

        // Cycle detection: An ancestor of the target folder in the target user tree is a child of the target folder in
        // another user tree. Then break the relation between the identified child and the target folder.
        $cycleFolderIds = $this->detectCycleBeforeReconstructFolderParent($userId, $folder->id, $folder->folder_parent_id);
        foreach ($cycleFolderIds as $cycleFolderId) {
            $this->foldersRelationsTable->moveItemFrom($cycleFolderId, [$folder->id]);
        }

        // Move the folder into the parent folder.
        $this->foldersRelationsTable->moveItemFor($folder->id, [$userId], $folder->folder_parent_id);

        return $folder->folder_parent_id;
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
        $userItems = $this->foldersRelationsTable->findByUserIdAndForeignModel($userId, PermissionsTable::FOLDER_ACO);

        $query = $this->foldersRelationsTable->find()
            ->where([
                'foreign_id' => $folderId,
                'folder_parent_id IN' => $userItems->select('foreign_id')
            ]);

        $exclude = Hash::get($options, 'exclude', []);
        if (!empty($exclude)) {
            $query->where([
                'folder_parent_id NOT IN' => $exclude
            ]);
        }

        return $query->select('folder_parent_id')
            ->distinct()
            ->extract('folder_parent_id')
            ->toArray();
    }

    /**
     * Detect a cycle before reconstructing an item parent
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
     * @param Folder $folder The target folder
     * @param string $userId The target user tree
     * @param bool $isPersonal (Optional) Is the permission added to a personal folder. Default false.
     * @return array The list of children that has been added to the folder
     */
    private function reconstructFolderChildrenFromOperatorTree(UserAccessControl $uac, Folder $folder, string $userId, bool $isPersonal = false)
    {
        $childrenIds = $this->getPotentialFolderChildrenIdsFromUserTree($uac->userId(), $folder->id, $userId);

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
            array_splice($conflictedFolderParentIds, array_search($folder->id, $conflictedFolderParentIds), 1);
            if (!empty($conflictedFolderParentIds)) {
                $this->foldersRelationsTable->moveItemFrom($childId, $conflictedFolderParentIds, null);
            }

            // Cycle detection: The identified child or one of its children in the target user tree is an ancestor of
            // the target folder in another user tree.
            $cyclesFoldersIds = $this->detectCycleBeforeReconstructFolderChildren($userId, $childId, $folder->id);
            // As the operator representation has the highest priority: break the cycle by moving the target folder from
            // the folder responsible of a cycle to the root of the users trees having this representation.
            foreach ($cyclesFoldersIds as $cyclesFolderId) {
                $this->foldersRelationsTable->moveItemFrom($folder->id, [$cyclesFolderId], null);
            }

            $this->foldersRelationsTable->moveItemFor($childId, [$userId], $folder->id);
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
                'user_id' => $fromUserIdTree
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
                'folder_parent_id' => $folderId
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
     * Reconstruct the parent of a folder in a user tree based on non operator users representation
     * @param UserAccessControl $uac The operator
     * @param Folder $folder The target folder
     * @param string $userId The target user
     */
    private function reconstructFolderParentFromOtherUsersTrees(UserAccessControl $uac, Folder $folder, string $userId)
    {
        $folderParentsIds = $this->getPotentialFolderParentsIds($folder->id, $userId);
        $folderParentsIdsCount = count($folderParentsIds);

        if (!$folderParentsIdsCount) {
            return;
        } elseif ($folderParentsIdsCount > 1) {
            // Conflict detection: Multiple folder parents.
            // We don't choose a version over another, the folder will be from the identified other parent folders to
            // the root.
            $this->foldersRelationsTable->moveItemFrom($folder->id, $folderParentsIds, null);
            return;
        } else {
            $folderParentId = $folderParentsIds[0];
        }

        // Cycle detection: An ancestor of the target folder in the target user tree is a child of the target folder in
        // another user tree.
        $cyclesFoldersIds = $this->detectCycleBeforeReconstructFolderParent($userId, $folder->id, $folderParentId);
        if (!empty($cyclesFoldersIds)) {
            // If a cycle is detected in the operator tree then:
            // - Do not apply the parent change;
            // - Break the parent folder relationship in other trees.
            $cyclesFoldersIdsInOperatorTree = $this->filterFoldersIdsByUserIdAccess($userId, $cyclesFoldersIds);
            if (!empty($cyclesFoldersIdsInOperatorTree)) {
                $this->foldersRelationsTable->moveItemFrom($folder->id, [$folderParentId]);
                return;
            }

            // If the cycle are relative to other users tree then: Move the detected cycle folder to the root for
            // each user seeing it in the target folder.
            foreach ($cyclesFoldersIds as $cyclesFolderId) {
                $this->foldersRelationsTable->moveItemFrom($cyclesFolderId, [$folder->id], null);
            }
        }

        // Move the folder into the parent parent.
        $this->foldersRelationsTable->moveItemFor($folder->id, [$userId], $folderParentId);
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
                'user_id' => $userId
            ])
            ->select('foreign_id')
            ->extract('foreign_id')
            ->toArray();
    }

    /**
     * Reconstruct the children of a folder in a user tree based on non operator users representation
     * @param UserAccessControl $uac The operator
     * @param Folder $folder The target folder
     * @param string $userId The target user
     * @param array $operatorFolderChildrenIds A list of items already added by the operator reconstruction process.
     */
    private function reconstructFolderChildrenFromOtherUsersTrees(UserAccessControl $uac, Folder $folder, string $userId, array $operatorFolderChildrenIds = [])
    {
        $childrenIds = $this->getPotentialFolderChildrenIds($folder->id, $userId);
        $childrenIds = array_diff($childrenIds, $operatorFolderChildrenIds);

        foreach ($childrenIds as $childId) {
            // Cycle detection: The identified child or one of its children in the target user tree is an ancestor of
            // the target folder in another user tree.
            $cyclesFoldersIds = $this->detectCycleBeforeReconstructFolderChildren($userId, $childId, $folder->id);

            // If a cycle is detected in the operator tree then:
            // - Do not apply the child change;
            // - Break the parent folder relationship in other trees.
            $cyclesFoldersIdsInOperatorTree = $this->filterFoldersIdsByUserIdAccess($uac->userId(), $cyclesFoldersIds);
            if (!empty($cyclesFoldersIdsInOperatorTree)) {
                $this->foldersRelationsTable->moveItemFrom($childId, [$folder->id]);
                return;
            }

            // If the cycle are relative to other users trees then: Move the target folder from the folder responsible
            // of a cycle to the root of the users trees having this representation.
            // @todo no test to validate this line
            foreach ($cyclesFoldersIds as $cyclesFolderId) {
                $this->foldersRelationsTable->moveItemFrom($folder->id, [$cyclesFolderId], null);
            }

            // Move the child into the target folder.
            $this->foldersRelationsTable->moveItemFor($childId, [$userId], $folder->id);
        }
    }

    /**
     * Update a permission on a folder.
     * @param Folder $folder The target folder
     * @param array $permissionData The permission data.
     */
    private function updatePermission(Folder $folder, array $permissionData)
    {
        $permissionId = $permissionData['id'];
        $originalPermissionsMatch = Hash::extract($folder->permissions, "{n}[id={$permissionId}]", []);
        // If the permissions to update is not found.
        if (empty($originalPermissionsMatch)) {
            // throw exception
        }
        $originalPermissionType = $originalPermissionsMatch[0]->type;

        $updatedPermissionType = Hash::get($permissionData, 'type');
//        var_dump($originalPermissionType, $updatedPermissionType);

        if ($originalPermissionType !== $updatedPermissionType) {
//            var_dump('update permission', $permissionData);
        } else {
//            var_dump('nothing to do for permission', $permissionData);
        }
//        $this->isPermissionChanged($folder->permissions, $permissionData)
    }

}
