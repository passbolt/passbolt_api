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

namespace Passbolt\Folders\Service\Folders;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Model\Table\ResourcesTable;
use App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;

class FoldersDeleteService
{
    use EventDispatcherTrait;

    public const FOLDERS_DELETE_FOLDER_EVENT = 'folders.folder.delete';

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersTable
     */
    private FoldersTable $foldersTable;

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    private FoldersRelationsTable $foldersRelationsTable;

    /**
     * @var \App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService
     */
    private PermissionsGetUsersIdsHavingAccessToService $getUsersIdsHavingAccessToService;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private PermissionsTable $permissionsTable;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    private ResourcesTable $resourcesTable;

    /**
     * @var \App\Service\Permissions\UserHasPermissionService
     */
    private UserHasPermissionService $userHasPermissionService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->resourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $this->getUsersIdsHavingAccessToService = new PermissionsGetUsersIdsHavingAccessToService();
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     * Delete a folder for the current user.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param string $id The folder to delete
     * @param bool $cascade (optional) Delete also the folder content. Default false.
     * @return void
     * @throws \Exception If an unexpected error occurred
     */
    public function delete(UserAccessControl $uac, string $id, bool $cascade = false): void
    {
        $folder = $this->getFolder($id);
        if (!$this->checkUserCanDelete($uac, PermissionsTable::FOLDER_ACO, $id)) {
            throw new ForbiddenException(__('You are not allowed to delete this folder.'));
        }

        $this->foldersTable->getConnection()->transactional(function () use ($uac, $folder, $cascade): void {
            $usersIds = $this->getUsersIdsHavingAccessToService->getUsersIdsHavingAccessTo($folder->id);
            $this->deleteFolder($uac, $folder, $cascade);
            $this->dispatchEvent(self::FOLDERS_DELETE_FOLDER_EVENT, [
                'uac' => $uac,
                'folder' => $folder,
                'users' => $usersIds,
            ]);
        });
    }

    /**
     * Retrieve the folder.
     *
     * @param string $folderId The folder identifier to retrieve.
     * @return \Passbolt\Folders\Model\Entity\Folder
     * @throws \Cake\Http\Exception\NotFoundException If the folder does not exist.
     */
    private function getFolder(string $folderId): Folder
    {
        try {
            return $this->foldersTable->get($folderId);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The folder does not exist.'));
        }
    }

    /**
     * Assert that the current user can update the destination folder.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param string $itemModel The target item model
     * @param string $itemId The target item
     * @return bool
     */
    private function checkUserCanDelete(UserAccessControl $uac, string $itemModel, string $itemId): bool
    {
        $userId = $uac->getId();

        return $this->userHasPermissionService->check($itemModel, $itemId, $userId, Permission::UPDATE);
    }

    /**
     * Delete a folder.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target folder
     * @param bool $cascade Should delete the content
     * @return void
     * @throws \Exception
     */
    private function deleteFolder(UserAccessControl $uac, Folder $folder, bool $cascade): void
    {
        if ($cascade) {
            $this->deleteFolderContentCascade($uac, $folder);
        } else {
            $this->moveFolderContentToRoot($folder);
        }

        $this->foldersTable->delete($folder, ['atomic' => false]);
        $this->foldersRelationsTable->deleteAll(['foreign_id' => $folder->id]);
        $this->permissionsTable->deleteAll(['aco_foreign_key' => $folder->id]);
    }

    /**
     * Delete the content of a folder recursively using iterative BFS and batch operations.
     *
     * Collects all descendants level by level, checks permissions in batch, then executes
     * bulk delete/move operations instead of recursive per-item queries.
     *
     * @param \App\Utility\UserAccessControl $uac The current user.
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target folder
     * @return void
     */
    private function deleteFolderContentCascade(UserAccessControl $uac, Folder $folder): void
    {
        $userId = $uac->getId();
        $deletableFolderIds = [];
        $deletableResourceIds = [];
        $nonDeletableItemIds = [];
        $seenItemIds = [];
        $currentFolderIds = [$folder->id];

        // Phase 1: BFS collection — traverse the tree level by level.
        while (!empty($currentFolderIds)) {
            $children = $this->foldersRelationsTable->find()
                ->select(['foreign_model', 'foreign_id'])
                ->where(['folder_parent_id IN' => $currentFolderIds])
                ->distinct(['foreign_model', 'foreign_id'])
                ->disableHydration()
                ->all()
                ->toArray();

            if (empty($children)) {
                break;
            }

            $childFolderIds = [];
            $childResourceIds = [];
            foreach ($children as $child) {
                $foreignId = $child['foreign_id'];
                if (isset($seenItemIds[$foreignId])) {
                    continue;
                }
                $seenItemIds[$foreignId] = true;

                if ($child['foreign_model'] === FoldersRelation::FOREIGN_MODEL_FOLDER) {
                    $childFolderIds[] = $foreignId;
                } else {
                    $childResourceIds[] = $foreignId;
                }
            }

            $allChildIds = array_merge($childFolderIds, $childResourceIds);
            if (empty($allChildIds)) {
                break;
            }

            $deletableItemIds = array_flip($this->batchCheckDeletable($userId, $allChildIds));

            $nextLevelFolderIds = [];
            foreach ($childFolderIds as $folderId) {
                if (isset($deletableItemIds[$folderId])) {
                    $deletableFolderIds[] = $folderId;
                    $nextLevelFolderIds[] = $folderId;
                } else {
                    $nonDeletableItemIds[] = $folderId;
                }
            }

            foreach ($childResourceIds as $resourceId) {
                if (isset($deletableItemIds[$resourceId])) {
                    $deletableResourceIds[] = $resourceId;
                } else {
                    $nonDeletableItemIds[] = $resourceId;
                }
            }

            $currentFolderIds = $nextLevelFolderIds;
        }

        // Phase 2: Batch execution.

        // Move non-deletable items from deleted folders to root.
        if (!empty($nonDeletableItemIds)) {
            $allDeletedFolderIds = array_merge([$folder->id], $deletableFolderIds);
            $this->foldersRelationsTable->updateAll(
                ['folder_parent_id' => null],
                [
                    'foreign_id IN' => $nonDeletableItemIds,
                    'folder_parent_id IN' => $allDeletedFolderIds,
                ]
            );
        }

        // Soft-delete resources individually to preserve afterSoftDelete event chain
        // which cleans up each resource's folder relations.
        if (!empty($deletableResourceIds)) {
            $resources = $this->resourcesTable->find()
                ->select(['Resources.id', 'Resources.resource_type_id', 'ResourceTypes.id', 'ResourceTypes.deleted'])
                ->contain(['ResourceTypes'])
                ->where(['Resources.id IN' => $deletableResourceIds])
                ->all();
            foreach ($resources as $resource) {
                // Cost here is massive:
                // 1 UPDATE (soft-delete the resource),
                // 4 DELETE queries (secrets, secret_revisions, permissions, favorites)
                // 1 event dispatch triggering ResourcesAfterSoftDeleteService::afterSoftDelete()
                // That's 6 queries per resource. For 100 resources, this is 600 queries inside the transaction.
                $this->resourcesTable->softDelete($userId, $resource, checkPermission: false);
            }
        }

        // Bulk delete descendant folders, their relations, and permissions.
        if (!empty($deletableFolderIds)) {
            $this->foldersRelationsTable->deleteAll(['foreign_id IN' => $deletableFolderIds]);
            $this->permissionsTable->deleteAll(['aco_foreign_key IN' => $deletableFolderIds]);
            $this->foldersTable->deleteAll(['id IN' => $deletableFolderIds]);
        }
    }

    /**
     * Batch check which items the user can delete (has UPDATE or higher permission).
     *
     * Uses the same permission resolution pattern as PermissionsFindersTrait::findAllByAro(),
     * checking both direct user permissions and inherited group permissions.
     *
     * @param string $userId The user identifier.
     * @param array<string> $itemIds The item IDs to check permissions for.
     * @return array<string> The subset of item IDs the user can delete.
     */
    private function batchCheckDeletable(string $userId, array $itemIds): array
    {
        if (empty($itemIds)) {
            return [];
        }

        // Subquery: user's group IDs + user ID (mirrors findAllByAro pattern).
        $aroForeignKeys = $this->permissionsTable->Groups->GroupsUsers->find()
            ->select('group_id')
            ->where(['user_id' => $userId])
            ->epilog('UNION SELECT :aroForeignKey')
            ->bind(':aroForeignKey', $userId);

        return $this->permissionsTable
            ->find()
            ->select(['aco_foreign_key'])
            ->distinct('aco_foreign_key')
            ->where([
                'aro_foreign_key IN' => $aroForeignKeys,
                'aco_foreign_key IN' => $itemIds,
                'type >=' => Permission::UPDATE,
            ])
            ->disableHydration()
            ->all()
            ->extract('aco_foreign_key')
            ->toArray();
    }

    /**
     * Move folder content to root.
     *
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target folder
     * @return void
     * @throws \Exception
     */
    private function moveFolderContentToRoot(Folder $folder): void
    {
        $this->foldersRelationsTable->updateAll(['folder_parent_id' => null], ['folder_parent_id' => $folder->id]);
    }
}
