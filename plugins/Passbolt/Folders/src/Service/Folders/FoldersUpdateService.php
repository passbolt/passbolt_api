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

use App\Error\Exception\CustomValidationException;
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
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Exception;
use Passbolt\Folders\Model\Behavior\ContainFolderParentIdBehavior;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsMoveItemInUserTreeService;

class FoldersUpdateService
{
    use EventDispatcherTrait;

    const FOLDERS_UPDATE_FOLDER_EVENT = 'folders.folder.update';

    /**
     * @var FoldersTable
     */
    public $foldersTable;

    /**
     * @var FoldersRelationsMoveItemInUserTreeService
     */
    private $foldersRelationsMoveItemInUserTreeService;

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
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->foldersRelationsMoveItemInUserTreeService = new FoldersRelationsMoveItemInUserTreeService();
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     * Update a folder for the current user.
     *
     * @param UserAccessControl $uac The current user
     * @param string $id The folder to update
     * @param array $data The folder data
     * @return Folder
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
                $this->updateFolderMeta($uac, $folder, $data['name']);
            }

            if (array_key_exists('folder_parent_id', $data)) {
                $folderParentId = $this->getAndValidateFolderParentId($folder, $data);
                $this->moveFolder($uac, $folder, $folderParentId);
                $folder->set('folder_parent_id', $folderParentId);
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
     * Update folder meta.
     *
     * @param UserAccessControl $uac The current user
     * @param Folder $folder The folder to update.
     * @param string $name The folder name
     * @return EntityInterface|Folder
     */
    private function updateFolderMeta(UserAccessControl $uac, Folder $folder, string $name)
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
    private function handleValidationErrors(Folder $folder)
    {
        $errors = $folder->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate folder data.'), $folder, $this->foldersTable);
        }
    }

    /**
     * Get and validate the folder parent id from the data.
     *
     * @param Folder $folder The target folder
     * @param array $data The data
     * @return string
     */
    private function getAndValidateFolderParentId(Folder $folder, array $data = [])
    {
        $folderParentId = Hash::get($data, 'folder_parent_id', null);

        if (!is_null($folderParentId) && !Validation::uuid($folderParentId)) {
            $errors = ['uuid' => 'The folder parent id is not valid.'];
            $folder->setError('folder_parent_id', $errors);
            $this->handleValidationErrors($folder);
        }

        return $folderParentId;
    }

    /**
     * Move the folder.
     *
     * @param UserAccessControl $uac The current user
     * @param Folder $folder The target folder
     * @param string|null $folderParentId The target destination folder
     * @return void
     * @throws Exception
     */
    private function moveFolder(UserAccessControl $uac, Folder $folder, string $folderParentId = null)
    {
        try {
            $this->foldersRelationsMoveItemInUserTreeService->move($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folder->id, $folderParentId);
        } catch (CustomValidationException $e) {
            $folder->setError('folder_parent_id', $e->getErrors());
            $this->handleValidationErrors($folder);
        }
    }
}
