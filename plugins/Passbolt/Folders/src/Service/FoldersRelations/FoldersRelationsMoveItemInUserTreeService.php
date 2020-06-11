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

namespace Passbolt\Folders\Service\FoldersRelations;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;

class FoldersRelationsMoveItemInUserTreeService
{
    /**
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var FoldersRelationsDetectStronglyConnectedComponents
     */
    private $folderRelationsDetectStronglyConnectedComponents;

    /**
     * @var FoldersRelationsRepairStronglyConnectedComponents
     */
    private $foldersRelationsRepairStronglyConnectedComponents;

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
        $this->folderRelationsDetectStronglyConnectedComponents = new FoldersRelationsDetectStronglyConnectedComponents();
        $this->foldersRelationsRepairStronglyConnectedComponents = new FoldersRelationsRepairStronglyConnectedComponents();
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     * Move a folder.
     *
     * @param UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The entity model
     * @param string $foreignId The entity id
     * @param string|null $folderParentId The destination folder. Null if root
     * @return void
     * @throws \Exception If an unexpected error occurred
     */
    public function move(UserAccessControl $uac, string $foreignModel, string $foreignId, string $folderParentId = FoldersRelation::ROOT)
    {
        $this->assertForeignItem($uac, $foreignModel, $foreignId);
        $this->assertFolderParent($uac, $folderParentId);
        $originalFolderParentId = $this->foldersRelationsTable->getItemFolderParentIdInUserTree($uac->userId(), $foreignId);
        $this->assertUserCanMoveOutOfFolder($uac, $foreignModel, $foreignId, $originalFolderParentId);
        $this->assertUserCanMoveInFolder($uac, $foreignModel, $foreignId, $folderParentId);

        $this->foldersRelationsTable->getConnection()->transactional(function () use ($uac, $foreignModel, $foreignId, $originalFolderParentId, $folderParentId) {
            $this->performMove($uac, $foreignModel, $foreignId, $originalFolderParentId, $folderParentId);
        });
    }

    /**
     * Assert that the foreign item exists in the user tree.
     *
     * @param UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The target item model
     * @param string $foreignId The target item id
     * @return void
     */
    private function assertForeignItem(UserAccessControl $uac, string $foreignModel, string $foreignId)
    {
        $exists = $this->foldersRelationsTable->isItemInUserTree($uac->userId(), $foreignId, $foreignModel);
        if (!$exists) {
            throw new NotFoundException(__("The {0} does not exist.", strtolower($foreignModel)));
        }
    }

    /**
     * Assert that the parent folder exists in the user tree.
     *
     * @param UserAccessControl $uac The user at the origin of the operation
     * @param string|null $folderParentId The folder parent id
     * @return void
     */
    private function assertFolderParent(UserAccessControl $uac, string $folderParentId = FoldersRelation::ROOT)
    {
        if ($folderParentId === FoldersRelation::ROOT) {
            return;
        }

        $exists = $this->foldersRelationsTable->isItemInUserTree($uac->userId(), $folderParentId);
        if (!$exists) {
            $errors = ['folder_parent_id' => ['folder_exists' => 'The folder parent does not exist.']];
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
     * Check if the user can move content out of the folder.
     * - User can always move content from root.
     * - User can always move content out of a personal folder.
     * - User can move content out of a shared folder if the user has at least an update permission on the folder to
     *   move and the original parent folder.
     *
     * @param UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The entity model
     * @param string $foreignId The entity id
     * @param string|null $originalFolderParentId The original folder location. Null if root
     * @return void
     */
    private function assertUserCanMoveOutOfFolder(UserAccessControl $uac, string $foreignModel, string $foreignId, string $originalFolderParentId = FoldersRelation::ROOT)
    {
        // User can always move content from root.
        if ($originalFolderParentId === FoldersRelation::ROOT) {
            return;
        }

        // User can always move content out of a personal folder.
        $isPersonal = $this->foldersRelationsTable->isItemPersonal($originalFolderParentId);
        if ($isPersonal) {
            return;
        }

        // User can move content out of a shared folder if the user has at least an update permission on the folder to
        // move and the original parent folder.
        $userId = $uac->userId();
        $isAllowedToMoveOut = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $originalFolderParentId, $userId, Permission::UPDATE);
        if (!$isAllowedToMoveOut) {
            $errors = ['folder_parent_id' => ['has_folder_access' => 'You are not allowed to move this item out of its parent folder.']];
            $this->handleValidationErrors($errors);
        }

        $isAllowedToMoveContent = $this->userHasPermissionService->check($foreignModel, $foreignId, $userId, Permission::UPDATE);
        if (!$isAllowedToMoveContent) {
            $errors = ['folder_parent_id' => ['has_access' => 'You are not allowed to move this item.']];
            $this->handleValidationErrors($errors);
        }
    }

    /**
     * Check if the user can move content in a folder.
     * - User can always move content to the root.
     * - User can move content with any permission into a personal folder.
     * - User can move content with sufficient permission (>UPDATE) into shared folder with sufficient permission (>UPDATE)
     *
     * @param UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The item model
     * @param string $foreignId The item id
     * @param string|null $folderParentId The destination folder location
     * @return void
     */
    private function assertUserCanMoveInFolder(UserAccessControl $uac, string $foreignModel, string $foreignId, string $folderParentId = FoldersRelation::ROOT)
    {
        if ($folderParentId === FoldersRelation::ROOT) {
            return;
        }

        $isFolderParentPersonal = $this->foldersRelationsTable->isItemPersonal($folderParentId);
        if ($isFolderParentPersonal) {
            return;
        }

        $isAllowedToMoveIn = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folderParentId, $uac->userId(), Permission::UPDATE);
        if (!$isAllowedToMoveIn) {
            $errors = ['folder_parent_id' => ['has_folder_access' => 'You are not allowed to create content into the parent folder.']];
            $this->handleValidationErrors($errors);
        }

        $isAllowedToMove = $this->userHasPermissionService->check($foreignModel, $foreignId, $uac->userId(), Permission::UPDATE);
        if (!$isAllowedToMove) {
            $errors = ['folder_parent_id' => ['has_access' => 'You are not allowed to move an item in read only into the parent folder.']];
            $this->handleValidationErrors($errors);
        }
    }

    /**
     * Move an item from a location to another.
     *
     * @param UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The entity model
     * @param string $foreignId The entity id
     * @param string $originalFolderParentId The original folder location
     * @param string $folderParentId The destination folder location
     * @return void
     */
    private function performMove(UserAccessControl $uac, string $foreignModel, string $foreignId, string $originalFolderParentId = FoldersRelation::ROOT, string $folderParentId = FoldersRelation::ROOT)
    {
        // If the folder is moved to the root, then only move it for all users having the same representation as the
        // operator.
        if ($folderParentId === FoldersRelation::ROOT) {
            $this->foldersRelationsTable->moveItemFrom($foreignId, [$originalFolderParentId], FoldersRelation::ROOT);
        } else {
            // Otherwise:
            // - Move the target item into the new destination for all users seeing the destination.
            // - Move the target item to the root of all users not seeing the destination folder but having the same
            //   representation as the user above.
            $usersIdsHavingAccessToItemAndDestinationFolder = $this->foldersRelationsTable->getUsersIdsHavingAccessToMultipleItems([$foreignId, $folderParentId]);
            $conflictedFolderParentIds = $this->foldersRelationsTable->getItemFoldersParentIdsInUsersTrees($usersIdsHavingAccessToItemAndDestinationFolder, $foreignId, true);
            if (!empty($conflictedFolderParentIds)) {
                $this->foldersRelationsTable->moveItemFrom($foreignId, $conflictedFolderParentIds, FoldersRelation::ROOT);
            }
            $this->foldersRelationsTable->moveItemFor($foreignId, $usersIdsHavingAccessToItemAndDestinationFolder, $folderParentId);

            if ($foreignModel === FoldersRelation::FOREIGN_MODEL_FOLDER) {
                $this->assertCycleFree($uac);
                $this->detectAndRepairStronglyConnectedComponents($uac, $uac->userId(), $usersIdsHavingAccessToItemAndDestinationFolder);
            }
        }
    }

    /**
     * Assert that the move doesn't create a cycle in the operator tree.
     *
     * @param UserAccessControl $uac The user at the origin of the operation
     * @return void
     */
    private function assertCycleFree(UserAccessControl $uac)
    {
        $scc = $this->folderRelationsDetectStronglyConnectedComponents->detectInUserTree($uac->userId());
        if (!empty($scc)) {
            $errors = ['folder_parent_id' => ['cycle' => 'The folder cannot be moved into one of its descendants.']];
            $this->handleValidationErrors($errors);
        }
    }

    /**
     * Look for strongly connected components between the users impacted by a change and other passbolt users.
     *
     * @param UserAccessControl $uac The user at the origin of the operation
     * @param string $userId The target user id the item is added for
     * @param array $usersIdsImpacted Users impacted by the change
     * @return void
     */
    private function detectAndRepairStronglyConnectedComponents(UserAccessControl $uac, string $userId, array $usersIdsImpacted)
    {
        $usersIdsToDetectSCCsFor = array_merge([$userId], $usersIdsImpacted);
        $scc = $this->folderRelationsDetectStronglyConnectedComponents->bulkDetectForUsers($usersIdsToDetectSCCsFor);
        if (empty($scc)) {
            return;
        }

        // Treat the first scc found.
        $this->foldersRelationsRepairStronglyConnectedComponents->repair($uac, $userId, $scc);

        // Run the repair function again in order to find others SCCs.
        $this->detectAndRepairStronglyConnectedComponents($uac, $userId, $usersIdsImpacted);
    }
}
