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

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Table\FoldersTable;

class FoldersUpdateService
{
    use EventDispatcherTrait;

    const FOLDERS_UPDATE_FOLDER_EVENT = 'folders.folder.update';

    /**
     * @var FoldersTable
     */
    private $foldersTable;

    /**
     * @var FoldersHasAncestorService
     */
    private $foldersHasAncestorService;

    /**
     * @var FoldersMoveService
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
        $this->foldersHasAncestorService = new FoldersHasAncestorService();
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
     * @throws \Exception If an unexpected error occurred
     */
    public function update(UserAccessControl $uac, string $id, array $data = [])
    {
        if (empty($data)) {
            return;
        }

        $folder = $this->getFolder($id);

        $this->foldersTable->getConnection()->transactional(function () use (&$folder, $uac, $data) {
            if (array_key_exists('name', $data)) {
                $this->updateFolder($uac, $folder, $data['name']);
            }
            if (array_key_exists('folder_parent_id', $data)) {
                $this->moveFolder($uac, $folder, $data['folder_parent_id']);
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
     * Update and save the folder changes in database.
     *
     * @param UserAccessControl $uac The current user
     * @param Folder $folder The folder to update.
     * @param string $name The folder name
     * @return \Cake\Datasource\EntityInterface|Folder
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
     * @return \Cake\Datasource\EntityInterface|Folder
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
     * Move the folder.
     *
     * @param UserAccessControl $uac The current user.
     * @param Folder $folder The folder to move.
     * @param string $folderParentId The destination folder.
     * @return void
     * @throws \Exception
     */
    private function moveFolder(UserAccessControl $uac, Folder $folder, string $folderParentId = null)
    {
        $this->validateMoveFolder($uac, $folder, $folderParentId);
        $this->foldersMoveService->move($uac, $folder->id, $folderParentId);
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
            $this->assertUserCanMoveFolderToRoot($folder);
        } else {
            $this->assertUserCanChangeTheFolderParent($folder);
            $this->assertFolderParentExists($folderParentId);
            $this->assertUserHasPermissionToUseFolderParent($uac, $folderParentId);
            $this->assertMoveIsCycleFree($uac, $folder, $folderParentId);
        }
    }

    private function assertUserCanChangeTheFolderParent(Folder $folder)
    {
        // @todo Not needed with personal folder.
    }

    /**
     * Assert that the user can move the folder to the root.
     *
     * @param Folder $folder The folder to move.
     * @return void
     */
    private function assertUserCanMoveFolderToRoot(Folder $folder)
    {
        // @todo Not needed with personal folder.
    }

    /**
     * Assert that the parent folder exists.
     *
     * @param string $folderId The destination folder.
     * @return void
     * @throws CustomValidationException If the destination folder does not exist.
     */
    private function assertFolderParentExists(string $folderId)
    {
        try {
            $this->foldersTable->get($folderId);
        } catch (RecordNotFoundException $e) {
            $errors = [
                'folder_parent_id' => [
                    'folder_exists' => 'The folder parent must exist.',
                ],
            ];
            throw new CustomValidationException(__('Could not validate the folder data.'), $errors);
        }
    }

    /**
     * Assert that the current user can update the destination folder.
     *
     * @param UserAccessControl $uac The current user
     * @param string $folderId The parent folder.
     * @return void
     * @throws CustomValidationException If the user cannot write in the destination folder.
     */
    private function assertUserHasPermissionToUseFolderParent(UserAccessControl $uac, string $folderId)
    {
        $userId = $uac->userId();
        $isAllowedToMoveIn = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folderId, $userId, Permission::UPDATE);
        if (!$isAllowedToMoveIn) {
            $errors = [
                'folder_parent_id' => [
                    'folder_exists' => 'The folder parent is not writable.',
                ],
            ];
            throw new ForbiddenException('Could not validate the folder data.', null, new CustomValidationException(__('Could not validate the folder data.'), $errors));
        }
    }

    /**
     * Assert that moving the folder into the destination folder won't create a cycle.
     *
     * @param UserAccessControl $uac The current user
     * @param Folder $folder The folder to move
     * @param string $folderParentId The parent folder.
     * @return void
     * @throws CustomValidationException If a cycle is detected
     */
    private function assertMoveIsCycleFree(UserAccessControl $uac, Folder $folder, string $folderParentId)
    {
        $cycle = $this->foldersHasAncestorService->check($uac, $folder->id, $folderParentId);
        if ($cycle) {
            $errors = [
                'folder_parent_id' => [
                    'folder_cycle' => 'The folder cannot be its own ancestor.',
                ],
            ];
            throw new CustomValidationException(__('Could not validate the folder data.'), $errors);
        }
    }
}
