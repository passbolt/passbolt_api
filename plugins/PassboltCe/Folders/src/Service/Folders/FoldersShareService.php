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

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Dto\EntitiesChangesDto;
use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\PermissionsUpdatePermissionsService;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Folders\Model\Behavior\FolderizableBehavior;
use Passbolt\Folders\Model\Dto\FolderRelationDto;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemsToUserTreeService;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsRemoveItemFromUserTreeService;

class FoldersShareService
{
    use EventDispatcherTrait;

    public const FOLDERS_SHARE_FOLDER_EVENT = 'folders.folder.share';

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersTable
     */
    private $foldersTable;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemsToUserTreeService
     */
    private $foldersRelationsAddItemsToUserTreeService;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsRemoveItemFromUserTreeService
     */
    private $foldersRelationsRemoveItemFromUserTreeService;

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var \App\Service\Permissions\PermissionsUpdatePermissionsService
     */
    private $permissionsUpdatePermissionsService;

    /**
     * @var \App\Service\Permissions\UserHasPermissionService
     */
    private $userHasPermissionService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');

        $this->foldersRelationsAddItemsToUserTreeService = new FoldersRelationsAddItemsToUserTreeService();
        $this->foldersRelationsRemoveItemFromUserTreeService = new FoldersRelationsRemoveItemFromUserTreeService();
        $this->permissionsUpdatePermissionsService = new PermissionsUpdatePermissionsService();
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     * Update a folder for the current user.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param string $id The folder to update
     * @param array|null $data The folder data
     * @return \Passbolt\Folders\Model\Entity\Folder
     * @throws \Exception If an unexpected error occurred
     */
    public function share(UserAccessControl $uac, string $id, ?array $data = []): Folder
    {
        $folder = $this->getFolder($id, $uac);
        $this->assertUserCanShare($uac, $folder);

        $permissionsData = Hash::get($data, 'permissions', []);
        if (empty($permissionsData)) {
            return $folder;
        }

        $this->foldersTable->getConnection()->transactional(function () use (&$folder, $uac, $permissionsData) {
            $isPersonal = $this->foldersRelationsTable->isItemPersonal($folder->id);
            $entitiesChanges = $this->updatePermissions($uac, $folder, $permissionsData);
            $addedPermissions = $entitiesChanges->getAddedEntities(Permission::class);
            $deletedPermissions = $entitiesChanges->getDeletedEntities(Permission::class);
            // If the folder was a personal folder. Then move the content that was self organized and for which the user
            // does not have sufficient permission onto it (<UPDATE) to move into a shared folder.
            if ($isPersonal && !empty($addedPermissions)) {
                $this->moveSelfOrganizedContentWithInsufficientPermissionToRoot($uac, $folder);
            }
            $this->postPermissionsRevoked($folder, $deletedPermissions);
            $this->postPermissionsAdded($uac, $folder, $addedPermissions);
        });

        return $folder;
    }

    /**
     * Retrieve the folder.
     *
     * @param string $folderId The folder identifier to retrieve.
     * @param \App\Utility\UserAccessControl $uac UserAccessControl updating the resource
     * @return \Passbolt\Folders\Model\Entity\Folder
     * @throws \Cake\Http\Exception\NotFoundException If the folder does not exist.
     */
    private function getFolder(string $folderId, UserAccessControl $uac): Folder
    {
        /** @var \Passbolt\Folders\Model\Entity\Folder|null $folder */
        $folder = $this->foldersTable->findById($folderId)
            ->find(FolderizableBehavior::FINDER_NAME, ['user_id' => $uac->getId()])
            ->first();

        if (empty($folder)) {
            throw new NotFoundException(__('The folder does not exist.'));
        }

        return $folder;
    }

    /**
     * Assert if the operator can share the given folder.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The folder to assert
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException If the user cannot share the folder
     */
    private function assertUserCanShare(UserAccessControl $uac, Folder $folder): void
    {
        $userId = $uac->getId();
        $isAllowed = $this->userHasPermissionService
            ->check(PermissionsTable::FOLDER_ACO, $folder->id, $userId, Permission::OWNER);
        if (!$isAllowed) {
            throw new ForbiddenException(__('You are not allowed to update the permissions of this folder.'));
        }
    }

    /**
     * Update a folder permissions
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target folder
     * @param array $changes The list of permissions changes
     * @return \App\Model\Dto\EntitiesChangesDto
     * @throws \App\Error\Exception\ValidationException If the permissions didn't validate
     * @throws \Exception If something went wrong
     */
    private function updatePermissions(UserAccessControl $uac, Folder $folder, array $changes): EntitiesChangesDto
    {
        $entitiesChanges = new EntitiesChangesDto();

        try {
            $entitiesChanges = $this->permissionsUpdatePermissionsService
                ->updatePermissions($uac, PermissionsTable::FOLDER_ACO, $folder->id, $changes);
        } catch (CustomValidationException $e) {
            $folder->setError('permissions', $e->getErrors());
            $this->handleValidationErrors($folder);
        }

        return $entitiesChanges;
    }

    /**
     * Handle folder validation errors.
     *
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target folder
     * @return void
     * @throws \App\Error\Exception\ValidationException If the provided data does not validate.
     */
    private function handleValidationErrors(Folder $folder): void
    {
        $errors = $folder->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate folder data.'), $folder, $this->foldersTable);
        }
    }

    /**
     * Move content of the folder which was self organized without sufficient permission (<UPDATE) to move it into
     * a shared folder to the root of the operator.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target shared folder
     * @return void
     */
    private function moveSelfOrganizedContentWithInsufficientPermissionToRoot(
        UserAccessControl $uac,
        Folder $folder
    ): void {
        $personalItems = $this->foldersRelationsTable
            ->findByUserIdAndFolderParentId($uac->getId(), $folder->id)
            ->select(['foreign_id', 'foreign_model'])
            ->toArray();
        foreach ($personalItems as $personalItem) {
            $canUpdate = $this->userHasPermissionService
                ->check($personalItem->foreign_model, $personalItem->foreign_id, $uac->getId(), Permission::UPDATE);
            if (!$canUpdate) {
                $this->foldersRelationsTable
                    ->moveItemFor($personalItem->foreign_id, [$uac->getId()], FoldersRelation::ROOT);
                continue;
            }
        }
    }

    /**
     * Post permissions revoked.
     *
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target folder
     * @param array|null $deletedPermissions The list of deleted permissions
     * @return void
     * @throws \Exception If something unexpected occurred
     */
    private function postPermissionsRevoked(Folder $folder, ?array $deletedPermissions = []): void
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
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target folder
     * @param string $groupId The target group
     * @return void
     * @throws \Exception
     */
    private function removeFolderFromGroupUsersTrees(Folder $folder, string $groupId): void
    {
        $grousUsersIds = $this->groupsUsersTable->findByGroupId($groupId)->all()->extract('user_id')->toArray();
        foreach ($grousUsersIds as $groupUserId) {
            $this->removeFolderFromUserTree($folder, $groupUserId);
        }
    }

    /**
     * Remove a folder from a user tree
     *
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target folder
     * @param string $userId The target user
     * @return void
     * @throws \Exception
     */
    private function removeFolderFromUserTree(Folder $folder, string $userId): void
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
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target folder
     * @param array|null $addedPermissions The list of added permissions
     * @return void
     * @throws \Exception If something unexpected occurred
     */
    private function postPermissionsAdded(UserAccessControl $uac, Folder $folder, ?array $addedPermissions = []): void
    {
        foreach ($addedPermissions as $permission) {
            if ($permission->aro === PermissionsTable::GROUP_ARO) {
                $this->addFolderToGroupUsersTrees($uac, $folder, $permission->aro_foreign_key);
            } else {
                $this->addFolderToUserTree($uac, $folder, $permission->aro_foreign_key);
            }
        }
    }

    /**
     * Add a folder to a group of users trees.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target folder
     * @param string $groupId The target group
     * @return void
     * @throws \Exception If something wrong occurred
     */
    private function addFolderToGroupUsersTrees(UserAccessControl $uac, Folder $folder, string $groupId): void
    {
        $groupsUsersIds = $this->groupsUsersTable->findByGroupId($groupId)->all()->extract('user_id')->toArray();
        foreach ($groupsUsersIds as $groupUserId) {
            $this->addFolderToUserTree($uac, $folder, $groupUserId);
        }
    }

    /**
     * Add a folder to a user tree.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \Passbolt\Folders\Model\Entity\Folder $folder The target folder
     * @param string $userId The target user
     * @return void
     * @throws \Exception If something wrong occurred
     */
    private function addFolderToUserTree(UserAccessControl $uac, Folder $folder, string $userId): void
    {
        $exists = $this->foldersRelationsTable->isItemInUserTree($userId, $folder->id);
        if ($exists) {
            return;
        }

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_FOLDER, $folder->id)];
        $this->foldersRelationsAddItemsToUserTreeService->addItemsToUserTree($uac, $userId, $items);
        $this->dispatchEvent(self::FOLDERS_SHARE_FOLDER_EVENT, [
            'uac' => $uac,
            'folder' => $folder,
            'userId' => $userId,
        ]);
    }
}
