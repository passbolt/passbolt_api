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

use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\PermissionsCreateService;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Exception;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsCreateService;

class FoldersCreateService
{
    use EventDispatcherTrait;

    public const FOLDERS_CREATE_FOLDER_EVENT = 'folders.folder.create';

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersTable
     */
    public $foldersTable;

    /**
     * @var \App\Service\Permissions\PermissionsCreateService
     */
    private $permissionsCreateService;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsCreateService
     */
    private $foldersRelationsCreateService;

    /**
     * @var \App\Service\Permissions\UserHasPermissionService
     */
    private $userHasPermissionService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->foldersRelationsCreateService = new FoldersRelationsCreateService();
        $this->permissionsCreateService = new PermissionsCreateService();
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     * Create a folder for the current user.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param array|null $data The folder data
     * @return \Passbolt\Folders\Model\Entity\Folder
     * @throws \Exception If an unexpected error occurred
     */
    public function create(UserAccessControl $uac, ?array $data = []): Folder
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
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param array $data The folder data
     * @return \Passbolt\Folders\Model\Entity\Folder
     */
    private function createFolder(UserAccessControl $uac, array $data): Folder
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
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param array $data The folder data
     * @return \Passbolt\Folders\Model\Entity\Folder
     */
    private function buildFolderEntity(UserAccessControl $uac, array $data): Folder
    {
        $userId = $uac->getId();
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
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The folder
     * @return void
     * @throws \App\Error\Exception\ValidationException If the provided data does not validate.
     */
    private function handleValidationErrors(Folder $folder)
    {
        $errors = $folder->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate folder data.'), $folder, $this->foldersTable);
        }
    }

    /**
     * Create the user permission for the created folder.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The folder
     * @return void
     */
    private function createPermission(UserAccessControl $uac, Folder $folder)
    {
        $userId = $uac->getId();
        $permissionData = [
            'aco' => PermissionsTable::FOLDER_ACO,
            'aco_foreign_key' => $folder->id,
            'aro' => PermissionsTable::USER_ARO,
            'aro_foreign_key' => $userId,
            'type' => Permission::OWNER,
        ];
        try {
            $this->permissionsCreateService->create($uac, $permissionData);
        } catch (Exception $error) {
            throw new InternalErrorException('Could not create the folder, please try again later.', 500, $error);
        }
    }

    /**
     * Create the folder relation.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The folder
     * @param array|null $data Optional data
     * $data.folder_parent_id $string The folder parent id
     * @return void
     */
    private function createFolderRelation(UserAccessControl $uac, Folder $folder, ?array $data = [])
    {
        $folderParentId = Hash::get($data, 'folder_parent_id', null);
        if (!is_null($folderParentId)) {
            $this->validateParentFolder($uac, $folder, $folderParentId);
            $this->handleValidationErrors($folder);
        }

        $folderRelationData = [
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'foreign_id' => $folder->id,
            'folder_parent_id' => $folderParentId,
            'user_id' => $uac->getId(),
        ];

        try {
            $this->foldersRelationsCreateService->create($folderRelationData);
            $folder->set('folder_parent_id', $folderParentId);
        } catch (Exception $e) {
            throw new InternalErrorException('Could not create the folder, please try again later.', 500, $e);
        }
    }

    /**
     * Validate the parent folder
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param \Passbolt\Folders\Model\Entity\Folder $folder the created folder
     * @param string $folderParentId The parent folder to validate
     * @return void
     * @throws \App\Error\Exception\CustomValidationException If the parent folder does not exist.
     * @throws \Cake\Http\Exception\ForbiddenException If the user is not allowed to insert content in the parent folder.
     */
    private function validateParentFolder(UserAccessControl $uac, Folder $folder, string $folderParentId)
    {
        if (!Validation::uuid($folderParentId)) {
            $errors = ['uuid' => __('The folder parent id should be a valid UUID.')];
            $folder->setError('folder_parent_id', $errors);

            return;
        }

        // The provided parent folder must exist.
        try {
            $this->foldersTable->get($folderParentId);
        } catch (RecordNotFoundException $e) {
            $errors = ['folder_exists' => __('The folder parent must exist.')];
            $folder->setError('folder_parent_id', $errors);

            return;
        }

        // The user should have at least UPDATE permission on the destination parent folder to insert content into.
        $userId = $uac->getId();
        $isAllowedToMoveIn = $this->userHasPermissionService
            ->check(PermissionsTable::FOLDER_ACO, $folderParentId, $userId, Permission::UPDATE);
        if (!$isAllowedToMoveIn) {
            $errors = ['has_folder_access' => __('You are not allowed to create content into the parent folder.')];

            $folder->setError('folder_parent_id', $errors);

            return;
        }
    }
}
