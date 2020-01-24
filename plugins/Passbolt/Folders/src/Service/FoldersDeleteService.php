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

namespace Passbolt\Folders\Service;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Model\Table\ResourcesTable;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Table\FoldersTable;

class FoldersDeleteService
{
    /**
     * @var FoldersTable
     */
    private $foldersTable;

    /**
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var FoldersRelationDeleteService
     */
    private $foldersRelationsDeleteService;

    /**
     * @var FoldersPermissionsDeleteService
     */
    private $foldersPermissionsDeleteService;

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
     * @throws \Exception If an unexpected error occurred
     */
    public function delete(UserAccessControl $uac, string $id, bool $cascade = false)
    {
        $folder = $this->getFolder($id);
        $this->assertUserCanDeleteFolder($uac, $id);

        $this->foldersTable->getConnection()->transactional(function () use (&$folder, $uac, $cascade) {
            if ($cascade) {
                $this->deleteContent($uac, $folder);
            }
            $this->deletePermissions($uac, $folder);
            $this->deleteFoldersRelations($uac, $folder);
            $this->deleteFolder($uac, $folder);
            $this->moveChildrenToRoot($uac, $folder);
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
     * @throws \Exception
     */
    private function deleteContent(UserAccessControl $uac, Folder $folder)
    {
        $userId = $uac->userId();
        $children = $this->foldersRelationsTable->find()
            ->where([
                'folder_parent_id' => $folder->id,
                'user_id' => $userId
            ]);

        foreach ($children as $folderRelation) {
            if ($folderRelation->foreign_model === 'Folder') {
                if ($this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folderRelation->foreign_id, $userId, Permission::UPDATE)) {
                    $this->delete($uac, $folderRelation->foreign_id, true);
                }
            } else if ($folderRelation->foreign_model === 'Resource') {
                $this->resourcesTable->softDelete($uac, $folderRelation->foreign_id);
            }
        }
    }

    /**
     * Delete the user permission for a folder.
     *
     * @param UserAccessControl $uac The current user.
     * @param Folder $folder The folder to delete the permission for.
     * @return void
     * @throws \Exception
     */
    private function deletePermissions(UserAccessControl $uac, Folder $folder)
    {
        $this->foldersPermissionsDeleteService->delete($uac, $folder->id);
    }

    /**
     * Delete the user folders relations.
     *
     * @param UserAccessControl $uac The current user.
     * @param Folder $folder The folder to delete the folders relations for.
     * @return void
     * @throws \Exception
     */
    private function deleteFoldersRelations(UserAccessControl $uac, Folder $folder)
    {
        $this->foldersRelationsDeleteService->delete($uac, $folder->id);
    }

    /**
     * Delete the folder in database.
     *
     * @param UserAccessControl $uac The current user.
     * @param Folder $folder The folder to delete
     * @return void
     * @throws \Exception
     */
    private function deleteFolder(UserAccessControl $uac, Folder $folder)
    {
        $this->foldersTable->delete($folder);
        $this->handleValidationErrors($folder);
        $this->foldersPermissionsDeleteService->delete($uac, $folder->id);
    }

    /**
     * Move the children of the folder to root.
     *
     * @param UserAccessControl $uac The current user.
     * @param Folder $folder The folder to delete
     * @return void
     * @throws \Exception
     */
    private function moveChildrenToRoot(UserAccessControl $uac, Folder $folder)
    {
        $userId = $uac->userId();
        $children = $this->foldersRelationsTable->find()
            ->where([
                'folder_parent_id' => $folder->id,
                'user_id' => $userId
            ])->select('id');

        $this->foldersRelationsTable->updateAll(['folder_parent_id' => null], ['id' => $children]);
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
