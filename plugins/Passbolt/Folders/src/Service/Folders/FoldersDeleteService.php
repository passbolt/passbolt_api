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
use Exception;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsDeleteService;

class FoldersDeleteService
{
    use EventDispatcherTrait;

    const FOLDERS_DELETE_FOLDER_EVENT = 'folders.folder.delete';

    /**
     * @var FoldersTable
     */
    private $foldersTable;

    /**
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var FoldersRelationsDeleteService
     */
    private $foldersRelationsDeleteService;

    /**
     * @var PermissionsGetUsersIdsHavingAccessToService
     */
    private $getUsersIdsHavingAccessToService;

    /**
     * @var PermissionsTable
     */
    private $permissionsTable;

    /**
     * @var ResourcesTable
     */
    private $resourcesTable;

    /**
     * @var UserHasPermissionService
     */
    private $userHasPermissionService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $this->getUsersIdsHavingAccessToService = new PermissionsGetUsersIdsHavingAccessToService();
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->resourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $this->userHasPermissionService = new UserHasPermissionService();
        $this->foldersRelationsDeleteService = new FoldersRelationsDeleteService();
    }

    /**
     * Delete a folder for the current user.
     *
     * @param UserAccessControl $uac The current user
     * @param string $id The folder to delete
     * @param bool $cascade (optional) Delete also the folder content. Default false.
     * @return void
     * @throws Exception If an unexpected error occurred
     */
    public function delete(UserAccessControl $uac, string $id, bool $cascade = false)
    {
        $folder = $this->getFolder($id);
        if (!$this->checkUserCanDelete($uac, PermissionsTable::FOLDER_ACO, $id)) {
            throw new ForbiddenException(__('You are not allowed to delete this folder.'));
        }

        $this->foldersTable->getConnection()->transactional(function () use ($uac, $folder, $cascade) {
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
     * @return Folder
     * @throws NotFoundException If the folder does not exist.
     */
    private function getFolder(string $folderId)
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
     * @param UserAccessControl $uac The current user
     * @param string $itemModel The target item model
     * @param string $itemId The target item
     * @return bool
     */
    private function checkUserCanDelete(UserAccessControl $uac, string $itemModel, string $itemId)
    {
        $userId = $uac->userId();

        return $this->userHasPermissionService->check($itemModel, $itemId, $userId, Permission::UPDATE);
    }

    /**
     * Delete a folder.
     *
     * @param UserAccessControl $uac The operator
     * @param Folder $folder The target folder
     * @param bool $cascade Should delete the content
     * @return void
     * @throws Exception
     */
    private function deleteFolder(UserAccessControl $uac, $folder, bool $cascade)
    {
        if ($cascade) {
            $this->deleteFolderChildrenOrMoveToRoot($uac, $folder);
        } else {
            $this->moveFolderContentToRoot($folder);
        }

        $this->foldersTable->delete($folder, ['atomic' => false]);
        $this->foldersRelationsTable->deleteAll(['foreign_id' => $folder->id]);
        $this->permissionsTable->deleteAll(['aco_foreign_key' => $folder->id]);
    }

    /**
     * Delete the content of a folder if possible, otherwise move it to the root.
     *
     * @param UserAccessControl $uac The current user.
     * @param Folder $folder The target folder
     * @return void
     * @throws Exception
     */
    private function deleteFolderChildrenOrMoveToRoot(UserAccessControl $uac, Folder $folder)
    {
        $children = $this->foldersRelationsTable->findAllByFolderParentId($folder->id);

        foreach ($children as $folderRelation) {
            $this->deleteFolderChildOrMoveToRoot($uac, $folderRelation->foreign_model, $folderRelation->foreign_id, $folder->id);
        }
    }

    /**
     * Delete a folder child if possible, otherwise move it to the root.
     *
     * @param UserAccessControl $uac The operator
     * @param string $foreignModel The type of child
     * @param string $foreignId The child identifier
     * @param string $folderParentId The parent folder identifier
     * @return void
     * @throws Exception
     */
    private function deleteFolderChildOrMoveToRoot(UserAccessControl $uac, string $foreignModel, string $foreignId, string $folderParentId)
    {
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
            $this->foldersRelationsTable->moveItemFrom($foreignId, [$folderParentId], null);
        }
    }

    /**
     * Delete a child resource
     *
     * @param UserAccessControl $uac The current user.
     * @param string $resourceId The resource to delete
     * @return void
     */
    private function deleteResource($uac, $resourceId)
    {
        $userId = $uac->userId();
        $canDelete = $this->userHasPermissionService->check(PermissionsTable::RESOURCE_ACO, $resourceId, $userId, Permission::UPDATE);
        if (!$canDelete) {
            throw new ForbiddenException('You cannot delete this resource');
        }

        $resource = $this->resourcesTable->get($resourceId);
        // The soft delete function will trigger an event that once caught will remove the resource from the users
        // folders trees.
        $this->resourcesTable->softDelete($uac->userId(), $resource);
    }

    /**
     * Move folder content to root.
     *
     * @param Folder $folder The target folder
     * @return void
     * @throws Exception
     */
    private function moveFolderContentToRoot(Folder $folder)
    {
        $this->foldersRelationsTable->updateAll(['folder_parent_id' => null], ['folder_parent_id' => $folder->id]);
    }
}
