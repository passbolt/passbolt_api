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

namespace Passbolt\Folders\Service\FoldersRelations;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;

class FoldersRelationsMoveItemInUserTreeService
{
    /**
     * @var FoldersItemsHasAncestorService
     */
    private $foldersItemsHasAncestorService;

    /**
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var FoldersTable
     */
    private $foldersTable;

    /**
     * @var UserHasPermissionService
     */
    private $userHasPermissionService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->foldersItemsHasAncestorService = new FoldersItemsHasAncestorService();
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     * Move a folder.
     *
     * @param UserAccessControl $uac The current user
     * @param string $foreignModel The item model
     * @param string $foreignId The item identifier
     * @param string|null $folderParentId The destination folder. Null if root
     * @return void
     */
    public function move(UserAccessControl $uac, string $foreignModel, string $foreignId, string $folderParentId = null)
    {
        $this->assertfolderParent($uac, $folderParentId);
        $originalFolderParentId = $this->getFolderParentId($uac, $foreignModel, $foreignId);
        $this->assertUserCanMoveOutOfFolder($uac, $foreignModel, $foreignId, $originalFolderParentId);
        $this->assertUserCanMoveInFolder($uac, $foreignModel, $foreignId, $folderParentId);
        $this->performMove($foreignId, $originalFolderParentId, $folderParentId);
    }

    /**
     * Assert that the parent folder exists in the user tree.
     *
     * @param UserAccessControl $uac The current user
     * @param string|null $folderParentId The folder parent id
     * @return void
     */
    private function assertFolderParent(UserAccessControl $uac, string $folderParentId = null)
    {
        if (is_null($folderParentId)) {
            return;
        }

        $exists = $this->foldersRelationsTable->findByForeignIdAndUserId($folderParentId, $uac->userId())->count() == 1;
        if (!$exists) {
            $errors = ['folder_exists' => 'The folder parent does not exist.'];
            $this->handleValidationErrors($errors);
        }
    }

    /**
     * Handle move validation errors.
     *
     * @param array $errors The list of errors
     * @return void
     * @throws CustomValidationException If the provided data does not validate.
     */
    private function handleValidationErrors(array $errors = [])
    {
        if (!empty($errors)) {
            throw new CustomValidationException(__('Could not validate move data.'), $errors, $this->foldersRelationsTable);
        }
    }

    /**
     * Retrieve an item folder parent id.
     *
     * @param UserAccessControl $uac The current user
     * @param string $foreignModel The item model
     * @param string $foreignId The item identifier
     * @return string
     */
    private function getFolderParentId(UserAccessControl $uac, string $foreignModel, string $foreignId)
    {
        return $this->foldersRelationsTable->findByForeignModelAndForeignIdAndUserId($foreignModel, $foreignId, $uac->userId())
            ->extract('folder_parent_id')
            ->first();
    }

    /**
     * Check if the user can move content out of the folder.
     * - User can always move content from root.
     * - User can always move content out of a personal folder.
     * - User can move content out of a shared folder if the user has at least an update permission on the folder to
     *   move and the original parent folder.
     *
     * @param UserAccessControl $uac The current user
     * @param string $foreingModel The item model
     * @param string $foreignId The idem it
     * @param string|null $folderParentId The original folder location
     * @return void
     */
    private function assertUserCanMoveOutOfFolder(UserAccessControl $uac, string $foreingModel, string $foreignId, string $folderParentId = null)
    {
        // User can always move content from root.
        if (is_null($folderParentId)) {
            return;
        }

        // User can always move content out of a personal folder.
        $isPersonal = $this->foldersRelationsTable->isPersonal($foreingModel, $foreignId);
        if ($isPersonal) {
            return;
        }

        // User can move content out of a shared folder if the user has at least an update permission on the folder to
        // move and the original parent folder.
        $userId = $uac->userId();
        $isAllowedToMoveOut = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folderParentId, $userId, Permission::UPDATE);
        if (!$isAllowedToMoveOut) {
            $errors = ['has_folder_access' => 'You are not allowed to move this item out of its parent folder.'];
            $this->handleValidationErrors($errors);
        }

        $isAllowedToMoveContent = $this->userHasPermissionService->check($foreingModel, $foreignId, $userId, Permission::UPDATE);
        if (!$isAllowedToMoveContent) {
            $errors = ['has_access' => 'You are not allowed to move this item.'];
            $this->handleValidationErrors($errors);
        }
    }

    /**
     * Check if the user can move content in a folder.
     * - User can always move content to the root.
     * - User can move content with any permission into a personal folder.
     * - User can move content with sufficient permission (>UPDATE) into shared folder with sufficient permission (>UPDATE)
     * - User can move content if the operation is not creating a cycle.
     *
     * @param UserAccessControl $uac The current user
     * @param string $foreignModel The item model
     * @param string $foreignId The item id
     * @param string|null $folderParentId The destination folder location
     * @return void
     */
    private function assertUserCanMoveInFolder(UserAccessControl $uac, string $foreignModel, string $foreignId, string $folderParentId = null)
    {
        if (is_null($folderParentId)) {
            return;
        }

        $userId = $uac->userId();

        $isFolderParentPersonal = $this->foldersRelationsTable->isPersonal(FoldersRelation::FOREIGN_MODEL_FOLDER, $folderParentId);
        if (!$isFolderParentPersonal) {
            $isAllowedToMoveIn = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folderParentId, $userId, Permission::UPDATE);
            if (!$isAllowedToMoveIn) {
                $errors = ['has_folder_access' => 'You are not allowed to create content into the parent folder.'];
                $this->handleValidationErrors($errors);
            }

            $isAllowedToMove = $this->userHasPermissionService->check($foreignModel, $foreignId, $userId, Permission::UPDATE);
            if (!$isAllowedToMove) {
                $errors = ['has_access' => 'You are not allowed to move this item.'];
                $this->handleValidationErrors($errors);
            }
        }

        // @todo document cycle detection.
        if ($foreignModel === FoldersRelation::FOREIGN_MODEL_FOLDER) {
            $hasAncestor = $this->foldersItemsHasAncestorService->hasAncestor($folderParentId, $foreignId, $uac->userId());
            if ($hasAncestor) {
                $errors = ['cycle' => 'The folder cannot be moved into one of its descendants.'];
                $this->handleValidationErrors($errors);
            }
        }

        // @todo check conflict detections in other trees.
        // Stop detection when a personal folder is met.
    }

    /**
     * Move an item from a location to another.
     *
     * @param string $foreignId The item to move
     * @param string|null $originalFolderParentId The original folder location. Null if root.
     * @param string|null $folderParentId The target folder location. Null if root
     * @return void
     */
    private function performMove(string $foreignId, string $originalFolderParentId = null, string $folderParentId = null)
    {
        // If the destination folder is not the root, then move the folder into the destination folder for users who can
        // see it.
        if (!is_null($folderParentId)) {
            $usersSeeingDestination = $this->foldersRelationsTable->findByForeignId($folderParentId)
                ->select('user_id')
                ->extract('user_id')
                ->toArray();
            if (!empty($usersSeeingDestination)) {
                $this->foldersRelationsTable->updateAll(['folder_parent_id' => $folderParentId], [
                    'foreign_id' => $foreignId,
                    'user_id IN' => $usersSeeingDestination,
                ]);
            }
        }

        // Move the folder to the root for rest of users who have it organized as the operator but cannot see the
        // destination folder (if any).
        if (!is_null($originalFolderParentId)) {
            $this->foldersRelationsTable->updateAll(['folder_parent_id' => null], [
                'foreign_id' => $foreignId,
                'folder_parent_id' => $originalFolderParentId,
            ]);
        }
    }
}
