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
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Exception;
use Passbolt\Folders\Model\Behavior\ContainFolderParentIdBehavior;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Service\FoldersItems\FoldersItemsHasAncestorService;

class FoldersUpdateService
{
    use EventDispatcherTrait;

    const FOLDERS_UPDATE_FOLDER_EVENT = 'folders.folder.update';

    /**
     * @var FoldersTable
     */
    private $foldersTable;

    /**
     * @var FoldersItemsHasAncestorService
     */
    private $foldersItemsHasAncestorService;

    /**
     * @var ResourcesMoveService
     */
    private $foldersMoveService;

    /**
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

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
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->usersTable = TableRegistry::getTableLocator()->get('Users');
        $this->foldersItemsHasAncestorService = new FoldersItemsHasAncestorService();
        $this->foldersMoveService = new FoldersMoveService();
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
    public function update(UserAccessControl $uac, string $id, array $data = [])
    {
        $folder = $this->getFolder($id, $uac);
        if (empty($data)) {
            return $folder;
        }

        $this->foldersTable->getConnection()->transactional(function () use (&$folder, $uac, $data) {
            if (array_key_exists('name', $data)) {
                $this->updateFolder($uac, $folder, $data['name']);
            }
            if (array_key_exists('folder_parent_id', $data)) {
                $this->validateMoveFolder($uac, $folder, $data['folder_parent_id']);
                $this->moveFolder($folder, $data['folder_parent_id']);
            }
        });

        $this->dispatchEvent(self::FOLDERS_UPDATE_FOLDER_EVENT, [
            'uac' => $uac,
            'folder' => $folder,
        ]);

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
            ]);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The folder does not exist.'));
        }
    }

    /**
     * Update and save the folder changes in database.
     *
     * @param UserAccessControl $uac The current user
     * @param Folder $folder The folder to update.
     * @param string $name The folder name
     * @return EntityInterface|Folder
     */
    private function updateFolder(UserAccessControl $uac, Folder $folder, string $name)
    {
        if ($folder->name === $name) {
            return $folder;
        }

        $userId = $uac->userId();
        $isAllowed = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folder->id, $userId, Permission::UPDATE);
        if (!$isAllowed) {
            throw new ForbiddenException(__('You are not allowed to update this folder.'));
        }

        $data = ['name' => $name];
        $this->patchEntity($folder, $data);
        $this->handleValidationErrors($folder);
        $this->foldersTable->save($folder);
        $this->handleValidationErrors($folder);

        return $folder;
    }

    /**
     * Patch the folder entity.
     *
     * @param Folder $folder The folder entity to update.
     * @param array $data The folder data.
     * @return EntityInterface|Folder
     */
    private function patchEntity($folder, $data)
    {
        $accessibleFields = [
            'name' => true,
        ];

        return $this->foldersTable->patchEntity($folder, $data, ['accessibleFields' => $accessibleFields]);
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
            throw new ValidationException(__('Could not validate the folder data.'), $folder, $this->foldersTable);
        }
    }

    /**
     * Validate the parent folder
     *
     * @param UserAccessControl $uac The current user
     * @param Folder $folder The folder to move.
     * @param string $folderParentId The destination folder.
     * @return void
     */
    private function validateMoveFolder(UserAccessControl $uac, Folder $folder, string $folderParentId = null)
    {
        if (is_null($folderParentId)) {
            $this->assertUserCanMoveOutOfFolder($uac, $folder);
        } else {
            $this->assertUserCanMoveOutOfFolder($uac, $folder);
            $this->assertFolderParentExists($folder, $folderParentId);
            $this->assertUserCanMoveInFolder($uac, $folder, $folderParentId);
            $this->assertMoveIsCycleFree($uac, $folder, $folderParentId);
        }
    }

    /**
     * Check if the user can move content out of the folder;
     * @param UserAccessControl $uac The current user
     * @param Folder $folder The folder from where the user wants to move out
     * @return void
     */
    private function assertUserCanMoveOutOfFolder(UserAccessControl $uac, Folder $folder)
    {
        if (!is_null($folder->folder_parent_id)) {
            $userId = $uac->userId();
            $isAllowedToMoveOut = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folder->folder_parent_id, $userId, Permission::UPDATE);
            if (!$isAllowedToMoveOut) {
                $errors = ['has_folder_access' => 'You are not allowed to move content out of the parent folder.'];
                $folder->setError('folder_parent_id', $errors);
                $this->handleValidationErrors($folder);
            }
        }
    }

    /**
     * Assert that the parent folder exists.
     *
     * @param Folder $folder The folder to move.
     * @param string $folderId The destination folder.
     * @return void
     */
    private function assertFolderParentExists(Folder $folder, string $folderId)
    {
        try {
            $this->foldersTable->get($folderId);
        } catch (RecordNotFoundException $e) {
            $errors = ['folder_exists' => 'The folder parent must exist.'];
            $folder->setError('folder_parent_id', $errors);
            $this->handleValidationErrors($folder);
        }
    }

    /**
     * Check if the user can move content in the folder;
     * @param UserAccessControl $uac The current user
     * @param Folder $folder The folder into which the user wants to move out
     * @param string $folderParentId The destination folder
     * @return void
     */
    private function assertUserCanMoveInFolder(UserAccessControl $uac, Folder $folder, string $folderParentId)
    {
        $userId = $uac->userId();
        $isAllowedToMoveIn = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folderParentId, $userId, Permission::UPDATE);
        if (!$isAllowedToMoveIn) {
            $errors = ['has_folder_access' => 'You are not allowed to create content into the parent folder.'];
            $folder->setError('folder_parent_id', $errors);
            $this->handleValidationErrors($folder);
        }
    }

    /**
     * Assert that moving the folder into the destination folder won't create a cycle.
     *
     * @param UserAccessControl $uac The current user
     * @param Folder $folder The folder to move
     * @param string $folderParentId The destination folder.
     * @return void
     */
    private function assertMoveIsCycleFree(UserAccessControl $uac, Folder $folder, string $folderParentId)
    {
        $hasAncestor = $this->foldersItemsHasAncestorService->hasAncestor($folderParentId, $folder->id, $uac->userId());
        if ($hasAncestor) {
            $errors = ['folder_cycle' => 'The destination folder cannot be a child.'];
            $folder->setError('folder_parent_id', $errors);
            $this->handleValidationErrors($folder);
        }
    }

    /**
     * Move the folder.
     *
     * @param Folder $folder The folder to move.
     * @param string $folderParentId The destination folder.
     * @return void
     * @throws Exception
     */
    private function moveFolder(Folder $folder, string $folderParentId = null)
    {
        // If the destination folder is not the root, then move the folder into it for users who can see it.
        if (!is_null($folderParentId)) {
            $usersSeeingDestination = $this->foldersRelationsTable->findByForeignId($folderParentId)->extract('user_id')->toArray();
            $this->foldersRelationsTable->updateAll(['folder_parent_id' => $folderParentId], [
                'foreign_id' => $folder->id,
                'user_id IN' => $usersSeeingDestination,
            ]);
        }

        // Move the folder to the root for rest of users who have it organized as the operator.
        $this->foldersRelationsTable->updateAll(['folder_parent_id' => null], [
            'foreign_id' => $folder->id,
            'folder_parent_id' => $folder->folder_parent_id,
        ]);

        $folder->set('folder_parent_id', $folderParentId);
    }
}
