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

use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Model\Table\ResourcesTable;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Exception;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Service\FoldersPermissionsDeleteService;
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
     * @var FoldersPermissionsDeleteService
     */
    private $foldersPermissionsDeleteService;

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
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->resourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $this->userHasPermissionService = new UserHasPermissionService();
        $this->foldersRelationsDeleteService = new FoldersRelationsDeleteService();
        $this->foldersPermissionsDeleteService = new FoldersPermissionsDeleteService();
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
        $this->assertUserCanDeleteFolder($uac, $id);

        $this->foldersTable->getConnection()->transactional(function () use (&$folder, $uac, $cascade) {
            if ($cascade) {
                $this->deleteFolderContent($uac, $folder);
            } else {
                $this->moveFolderContentToRoot($folder);
            }
            $this->foldersTable->delete($folder, ['atomic' => false]);
            $this->handleValidationErrors($folder);

            $this->dispatchEvent(self::FOLDERS_DELETE_FOLDER_EVENT, [
                'uac' => $uac,
                'folder' => $folder,
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
     * @param string $folderId The folder to check.
     * @return void
     * @throws ForbiddenException If the user is not allowed to delete the folder.
     */
    private function assertUserCanDeleteFolder(UserAccessControl $uac, string $folderId)
    {
        $userId = $uac->userId();
        $isAllowed = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folderId, $userId, Permission::UPDATE);
        if (!$isAllowed) {
            throw new ForbiddenException(__('You are not allowed to delete this folder.'));
        }
    }

    /**
     * Delete the content of a folder.
     * @param UserAccessControl $uac The current user.
     * @param Folder $folder The folder to delete the content
     * @return void
     * @throws Exception
     */
    private function deleteFolderContent(UserAccessControl $uac, Folder $folder)
    {
        $children = $this->foldersRelationsTable->findAllByFolderParentId($folder->id);

        foreach ($children as $folderRelation) {
            if ($folderRelation->foreign_model === FoldersRelation::FOREIGN_MODEL_FOLDER) {
                $this->deleteChildFolderOrMoveToRoot($uac, $folderRelation->foreign_id);
            } elseif ($folderRelation->foreign_model === FoldersRelation::FOREIGN_MODEL_RESOURCE) {
                $this->deleteChildResourceOrMoveToRoot($uac, $folderRelation->foreign_id);
            }
        }
    }

    /**
     * Delete a child folder
     * @param UserAccessControl $uac The current user.
     * @param string $folderId The folder to delete
     * @throws Exception
     */
    private function deleteChildFolderOrMoveToRoot($uac, $folderId)
    {
        $userId = $uac->userId();
        $canDelete = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folderId, $userId, Permission::UPDATE);
        if ($canDelete) {
            $this->delete($uac, $folderId, true);
        } else {
            $this->foldersRelationsTable->updateAll(['folder_parent_id' => null], ['foreign_id' => $folderId]);
        }
    }

    /**
     * Delete a child resource
     * @param UserAccessControl $uac The current user.
     * @param string $resourceId The resource to delete
     */
    private function deleteChildResourceOrMoveToRoot($uac, $resourceId)
    {
        $userId = $uac->userId();
        $canDelete = $this->userHasPermissionService->check(PermissionsTable::RESOURCE_ACO, $resourceId, $userId, Permission::UPDATE);
        if ($canDelete) {
            $resource = $this->resourcesTable->get($resourceId);
            $this->resourcesTable->softDelete($uac->userId(), $resource);
        } else {
            $this->foldersRelationsTable->updateAll(['folder_parent_id' => null], ['foreign_id' => $resourceId]);
        }
    }

    /**
     * Move folder content to root.
     *
     * @param Folder $folder The folder to delete
     * @return void
     * @throws Exception
     */
    private function moveFolderContentToRoot(Folder $folder)
    {
        $this->foldersRelationsTable->updateAll(['folder_parent_id' => null], ['folder_parent_id' => $folder->id]);
    }

    /**
     * Handle folder validation errors.
     *
     * @param Folder $folder The folder
     * @return void
     * @throws ValidationException If the provided data does not validate.
     */
    private function handleValidationErrors($folder)
    {
        $errors = $folder->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not delete the folder.'), $folder, $this->foldersTable);
        }
    }
}
