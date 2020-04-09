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

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Table\GroupsUsersTable;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\PermissionsUpdatePermissionsService;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Exception;
use Passbolt\Folders\Model\Behavior\ContainFolderParentIdBehavior;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemToUserTreeService;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsRemoveItemFromUserTreeService;

class FoldersShareService
{
    use EventDispatcherTrait;

    /**
     * @var FoldersTable
     */
    private $foldersTable;

    /**
     * @var FoldersRelationsAddItemToUserTreeService
     */
    private $foldersRelationsAddItemToUserTreeService;

    /**
     * @var FoldersRelationsRemoveItemFromUserTreeService
     */
    private $foldersRelationsRemoveItemFromUserTreeService;

    /**
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var PermissionsUpdatePermissionsService
     */
    private $permissionsUpdatePermissionsService;

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
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $this->foldersRelationsAddItemToUserTreeService = new FoldersRelationsAddItemToUserTreeService();
        $this->foldersRelationsRemoveItemFromUserTreeService = new FoldersRelationsRemoveItemFromUserTreeService();
        $this->permissionsUpdatePermissionsService = new PermissionsUpdatePermissionsService();
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
    public function share(UserAccessControl $uac, string $id, array $data = [])
    {
        $folder = $this->getFolder($id, $uac);
        $this->assertUserCanShare($uac, $folder);

        $permissionsData = Hash::get($data, 'permissions', []);
        if (empty($permissionsData)) {
            return $folder;
        }

        $this->foldersTable->getConnection()->transactional(function () use (&$folder, $uac, $permissionsData) {
            $isPersonal = $this->foldersRelationsTable->isPersonal(FoldersRelation::FOREIGN_MODEL_FOLDER, $folder->id);
            $result = $this->updatePermissions($uac, $folder, $permissionsData);
            $this->postPermissionsRevoked($folder, $result['removed']);
            $this->postPermissionsAdded($uac, $folder, $isPersonal, $result['added']);
        });

//        $this->dispatchEvent(self::FOLDERS_UPDATE_FOLDER_EVENT, [
//            'uac' => $uac,
//            'folder' => $folder,
//        ]);

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
     * Assert if the operator can share the given folder.
     *
     * @param UserAccessControl $uac The operator
     * @param Folder $folder The folder to assert
     * @return void
     * @throws ForbiddenException If the user cannot share the folder
     */
    private function assertUserCanShare(UserAccessControl $uac, Folder $folder)
    {
        $userId = $uac->userId();
        $isAllowed = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folder->id, $userId, Permission::OWNER);
        if (!$isAllowed) {
            throw new ForbiddenException(__('You are not allowed to update the permissions of this folder.'));
        }
    }

    /**
     * Update a folder permissions
     *
     * @param UserAccessControl $uac The operator
     * @param Folder $folder The target folder
     * @param array $changes The list of permissions changes
     * @return array
     * @throws ValidationException If the permissions didn't validate
     * @throws \Exception If something went wrong
     */
    private function updatePermissions(UserAccessControl $uac, Folder $folder, array $changes)
    {
        try {
            return $this->permissionsUpdatePermissionsService->updatePermissions($uac, PermissionsTable::FOLDER_ACO, $folder->id, $changes);
        } catch (CustomValidationException $e) {
            $folder->setError('permissions', $e->getErrors());
            $this->handleValidationErrors($folder);
        }
    }

    /**
     * Handle folder validation errors.
     *
     * @param Folder $folder The target folder
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
     * Post permissions revoked.
     *
     * @param Folder $folder The target folder
     * @param array $deletedPermissions The list of deleted permissions
     * @return void
     * @throws Exception If something unexpected occurred
     */
    private function postPermissionsRevoked(Folder $folder, array $deletedPermissions = [])
    {
        foreach ($deletedPermissions as $permission) {
            if ($permission->aro === PermissionsTable::GROUP_ARO) {
                $this->removeFolderFromGroupUsersTrees($folder, $permission->aro_foreign_key);
            } else {
                $this->removeFolderFromUserTree($folder, $permission->aro_foreign_key);
            }
        }
    }

    /**
     * Remove a folder from a group of users trees.
     *
     * @param Folder $folder The target folder
     * @param string $groupId The target group
     * @return void
     * @throws Exception
     */
    private function removeFolderFromGroupUsersTrees(Folder $folder, string $groupId)
    {
        $grousUsersIds = $this->groupsUsersTable->findByGroupId($groupId)->extract('user_id')->toArray();
        foreach ($grousUsersIds as $groupUserId) {
            $this->removeFolderFromUserTree($folder, $groupUserId);
        }
    }

    /**
     * Remove a folder from a user tree
     *
     * @param Folder $folder The target folder
     * @param string $userId The target user
     * @return void
     * @throws \Exception
     */
    private function removeFolderFromUserTree(Folder $folder, string $userId)
    {
        // If the user still has access to the folder, don't alter the user tree.
        $hasAccess = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folder->id, $userId);
        if ($hasAccess) {
            return;
        }

        $this->foldersRelationsRemoveItemFromUserTreeService->removeItemFromUserTree($folder->id, $userId, true);
    }

    /**
     * Post permissions added.
     *
     * @param UserAccessControl $uac The operator
     * @param Folder $folder The target folder
     * @param bool $isPersonal Was the folder personal before shared with other users.
     * @param array $addedPermissions The list of added permissions
     * @return void
     * @throws Exception If something unexpected occurred
     */
    private function postPermissionsAdded(UserAccessControl $uac, Folder $folder, bool $isPersonal, array $addedPermissions = [])
    {
        foreach ($addedPermissions as $permission) {
            if ($permission->aro === PermissionsTable::GROUP_ARO) {
                $this->addFolderToGroupUsersTrees($uac, $folder, $permission->aro_foreign_key, $isPersonal);
            } else {
                $this->addFolderToUserTree($uac, $folder, $permission->aro_foreign_key, $isPersonal);
            }
        }
    }

    /**
     * Add a folder to a group of users trees.
     *
     * @param UserAccessControl $uac The operator
     * @param Folder $folder The target folder
     * @param string $groupId The target group
     * @param bool $isPersonal (Optional) Is the permission added to a personal folder. Default false.
     * @return void
     * @throws Exception If something wrong occurred
     */
    private function addFolderToGroupUsersTrees(UserAccessControl $uac, Folder $folder, string $groupId, bool $isPersonal = false)
    {
        $grousUsersIds = $this->groupsUsersTable->findByGroupId($groupId)->extract('user_id')->toArray();
        foreach ($grousUsersIds as $groupUserId) {
            $this->addFolderToUserTree($uac, $folder, $groupUserId, $isPersonal);
        }
    }

    /**
     * Add a folder to a user tree.
     *
     * @param UserAccessControl $uac The operator
     * @param Folder $folder The target folder
     * @param string $userId The target user
     * @param bool $isPersonal (Optional) Is the permission added to a personal folder. Default false.
     * @return void
     * @throws Exception If something wrong occurred
     */
    private function addFolderToUserTree(UserAccessControl $uac, Folder $folder, string $userId, bool $isPersonal = false)
    {
        $this->foldersRelationsAddItemToUserTreeService->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folder->id, $userId, $isPersonal);
    }
}
