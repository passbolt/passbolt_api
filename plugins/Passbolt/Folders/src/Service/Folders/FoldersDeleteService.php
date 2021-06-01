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
use App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;

class FoldersDeleteService
{
    use EventDispatcherTrait;
    use ModelAwareTrait;

    public const FOLDERS_DELETE_FOLDER_EVENT = 'folders.folder.delete';

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersTable
     */
    private $Folders;

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    private $FoldersRelations;

    /**
     * @var \App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService
     */
    private $getUsersIdsHavingAccessToService;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $Permissions;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    private $Resources;

    /**
     * @var \App\Service\Permissions\UserHasPermissionService
     */
    private $userHasPermissionService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->loadModel('Passbolt/Folders.Folders');
        $this->loadModel('Passbolt/Folders.FoldersRelations');
        $this->loadModel('Permissions');
        $this->loadModel('Resources');
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

        $this->Folders->getConnection()->transactional(function () use ($uac, $folder, $cascade) {
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
            return $this->Folders->get($folderId);
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
            $this->deleteFolderChildrenOrMoveToRoot($uac, $folder);
        } else {
            $this->moveFolderContentToRoot($folder);
        }

        $this->Folders->delete($folder, ['atomic' => false]);
        $this->FoldersRelations->deleteAll(['foreign_id' => $folder->id]);
        $this->Permissions->deleteAll(['aco_foreign_key' => $folder->id]);
    }

    /**
     * Delete the content of a folder if possible, otherwise move it to the root.
     *
     * @param \App\Utility\UserAccessControl $uac The current user.
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target folder
     * @return void
     * @throws \Exception
     */
    private function deleteFolderChildrenOrMoveToRoot(UserAccessControl $uac, Folder $folder): void
    {
        $children = $this->FoldersRelations->findByFolderParentId($folder->id);

        foreach ($children as $folderRelation) {
            $this->deleteFolderChildOrMoveToRoot(
                $uac,
                $folderRelation->foreign_model,
                $folderRelation->foreign_id,
                $folder->id
            );
        }
    }

    /**
     * Delete a folder child if possible, otherwise move it to the root.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param string $foreignModel The type of child
     * @param string $foreignId The child identifier
     * @param string $folderParentId The parent folder identifier
     * @return void
     * @throws \Exception
     */
    private function deleteFolderChildOrMoveToRoot(
        UserAccessControl $uac,
        string $foreignModel,
        string $foreignId,
        string $folderParentId
    ): void {
        $canDelete = $this->checkUserCanDelete($uac, $foreignModel, $foreignId);
        if ($canDelete) {
            switch ($foreignModel) {
                case FoldersRelation::FOREIGN_MODEL_FOLDER:
                    $folder = $this->getFolder($foreignId);
                    $this->deleteFolder($uac, $folder, true);
                    break;
                case FoldersRelation::FOREIGN_MODEL_RESOURCE:
                    $this->deleteResource($uac, $foreignId);
                    break;
            }
        } else {
            $this->FoldersRelations->moveItemFrom($foreignId, [$folderParentId], null);
        }
    }

    /**
     * Delete a child resource
     *
     * @param \App\Utility\UserAccessControl $uac The current user.
     * @param string $resourceId The resource to delete
     * @return void
     */
    private function deleteResource(UserAccessControl $uac, string $resourceId): void
    {
        $userId = $uac->getId();
        $canDelete = $this->userHasPermissionService
            ->check(PermissionsTable::RESOURCE_ACO, $resourceId, $userId, Permission::UPDATE);
        if (!$canDelete) {
            throw new ForbiddenException(__('You cannot delete this resource'));
        }

        $resource = $this->Resources->get($resourceId);
        // The soft delete function will trigger an event that once caught will remove the resource from the users
        // folders trees.
        $this->Resources->softDelete($uac->getId(), $resource);
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
        $this->FoldersRelations->updateAll(['folder_parent_id' => null], ['folder_parent_id' => $folder->id]);
    }
}
