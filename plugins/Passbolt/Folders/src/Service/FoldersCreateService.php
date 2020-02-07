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
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Exception;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersTable;

class FoldersCreateService
{
    use EventDispatcherTrait;

    const FOLDERS_CREATE_FOLDER_EVENT = 'folders.folder.create';

    /**
     * @var FoldersTable
     */
    private $foldersTable;

    /**
     * @var FoldersPermissionsCreateService
     */
    private $foldersPermissionsCreateService;

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
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->foldersRelationsCreateService = new FoldersRelationsCreateService();
        $this->foldersPermissionsCreateService = new FoldersPermissionsCreateService();
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     * Create a folder for the current user.
     *
     * @param UserAccessControl $uac The current user
     * @param array $data The folder data
     * @return Folder
     * @throws Exception If an unexpected error occurred
     */
    public function create(UserAccessControl $uac, array $data = [])
    {
        $folder = null;

        $this->foldersTable->getConnection()->transactional(function () use (&$folder, $uac, $data) {
            $folder = $this->createFolder($uac, $data);
            $this->createPermission($uac, $folder);
            $this->createFolderRelation($uac, $folder, $data);
        });

        $this->dispatchEvent(self::FOLDERS_CREATE_FOLDER_EVENT, [
            'uac' => $uac,
            'folder' => $folder,
        ]);

        return $folder;
    }

    /**
     * Create and save the folder in database.
     *
     * @param UserAccessControl $uac The current user
     * @param array $data The folder data
     * @return EntityInterface|Folder
     */
    private function createFolder(UserAccessControl $uac, array $data)
    {
        $folder = $this->buildFolderEntity($uac, $data);
        $this->handleValidationErrors($folder);
        $this->foldersTable->save($folder);
        $this->handleValidationErrors($folder);

        return $folder;
    }

    /**
     * Build the folder entity.
     *
     * @param UserAccessControl $uac The current user
     * @param array $data The folder data
     * @return EntityInterface|Folder
     */
    private function buildFolderEntity(UserAccessControl $uac, array $data)
    {
        $userId = $uac->userId();
        $data = [
            'name' => Hash::get($data, 'name'),
            'created_by' => $userId,
            'modified_by' => $userId,
        ];
        $accessibleFields = [
            'name' => true,
            'created_by' => true,
            'modified_by' => true,
        ];

        return $this->foldersTable->newEntity($data, ['accessibleFields' => $accessibleFields]);
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
            throw new ValidationException(__('Could not validate the folder data.'), $folder, $this->foldersTable);
        }
    }

    /**
     * Create the user permission for the created folder.
     *
     * @param UserAccessControl $uac The current user
     * @param Folder $folder The folder
     * @return void
     */
    private function createPermission(UserAccessControl $uac, Folder $folder)
    {
        try {
            $this->foldersPermissionsCreateService->create($uac, $folder->id, Permission::OWNER);
        } catch (Exception $error) {
            throw new InternalErrorException(__('Could not create the folder, please try again later.'), 400, $error);
        }
    }

    /**
     * Create the folder relation.
     *
     * @param UserAccessControl $uac The current user
     * @param Folder $folder The folder
     * @param array $data Optional data
     * $data.folder_parent_id $string The folder parent id
     * @return void
     */
    private function createFolderRelation(UserAccessControl $uac, Folder $folder, array $data = [])
    {
        $folderParentId = Hash::get($data, 'folder_parent_id', null);
        if (!is_null($folderParentId)) {
            $this->validateParentFolder($uac, $folderParentId);
        }

        try {
            $this->foldersRelationsCreateService->create($uac, $folder->id, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderParentId);
            $folder->set('folder_parent_id', $folderParentId);
        } catch (Exception $e) {
            throw new InternalErrorException(__('Could not create the folder, please try again later.'), 500, $e);
        }
    }

    /**
     * Validate the parent folder
     *
     * @param UserAccessControl $uac The current user
     * @param string $folderParentId The parent folder to validate
     * @return void
     * @throws CustomValidationException If the parent folder does not exist.
     * @throws ForbiddenException If the user is not allowed to insert content in the parent folder.
     */
    private function validateParentFolder(UserAccessControl $uac, string $folderParentId)
    {
        // The provided parent folder must exist.
        try {
            $this->foldersTable->get($folderParentId);
        } catch (RecordNotFoundException $e) {
            $errors = [
                'folder_parent_id' => [
                    'folder_exists' => 'The folder parent must exist.',
                ],
            ];
            throw new CustomValidationException(__('Could not validate the folder data.'), $errors);
        }

        // The user should have at least UPDATE permission on the destination parent folder to insert content into.
        $userId = $uac->userId();
        $isAllowedToMoveIn = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folderParentId, $userId, Permission::UPDATE);
        if (!$isAllowedToMoveIn) {
            throw new ForbiddenException(__('You are not allowed to create content into the parent folder.'));
        }
    }
}
