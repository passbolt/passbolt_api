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

namespace Passbolt\Folders\Service\FoldersRelations;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;

class FoldersRelationsMoveItemInUserTreeService
{
    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsDetectStronglyConnectedComponentsService
     */
    private $folderRelationsDetectStronglyConnectedComponents;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsRepairStronglyConnectedComponentsService
     */
    private $foldersRelationsRepairStronglyConnectedComponents;

    /**
     * @var \App\Service\Permissions\UserHasPermissionService
     */
    private $userHasPermissionService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
        $this->folderRelationsDetectStronglyConnectedComponents = new FoldersRelationsDetectStronglyConnectedComponentsService(); //phpcs:ignore
        $this->foldersRelationsRepairStronglyConnectedComponents = new FoldersRelationsRepairStronglyConnectedComponentsService(); //phpcs:ignore
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     * Move a folder.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The entity model
     * @param string $foreignId The entity id
     * @param string|null $folderParentId The destination folder. Null if root
     * @return void
     * @throws \Exception If an unexpected error occurred
     */
    public function move(
        UserAccessControl $uac,
        string $foreignModel,
        string $foreignId,
        ?string $folderParentId = FoldersRelation::ROOT
    ): void {
        $this->assertForeignItem($uac, $foreignModel, $foreignId);
        $this->assertFolderParent($uac, $folderParentId);
        $originalFolderParentId = $this->foldersRelationsTable
            ->getItemFolderParentIdInUserTree($uac->getId(), $foreignId);
        $this->assertUserCanMoveOutOfFolder($uac, $foreignModel, $foreignId, $originalFolderParentId);
        $this->assertUserCanMoveInFolder($uac, $foreignModel, $foreignId, $folderParentId);

        $this->foldersRelationsTable->getConnection()->transactional(
            function () use ($uac, $foreignModel, $foreignId, $originalFolderParentId, $folderParentId) {
                $this->performMove($uac, $foreignModel, $foreignId, $originalFolderParentId, $folderParentId);
            }
        );
    }

    /**
     * Assert that the foreign item exists in the user tree.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The target item model
     * @param string $foreignId The target item id
     * @return void
     */
    private function assertForeignItem(UserAccessControl $uac, string $foreignModel, string $foreignId): void
    {
        $exists = $this->foldersRelationsTable->isItemInUserTree($uac->getId(), $foreignId, $foreignModel);
        if (!$exists) {
            throw new NotFoundException(__('The object to move does not exist.'));
        }
    }

    /**
     * Assert that the parent folder exists in the user tree.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string|null $folderParentId The folder parent id
     * @return void
     */
    private function assertFolderParent(UserAccessControl $uac, ?string $folderParentId = FoldersRelation::ROOT): void
    {
        if ($folderParentId === FoldersRelation::ROOT) {
            return;
        }

        $exists = $this->foldersRelationsTable->isItemInUserTree($uac->getId(), $folderParentId);
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
     * @throws \App\Error\Exception\CustomValidationException If the provided data does not validate.
     */
    private function handleValidationErrors(?array $errors = []): void
    {
        if (!empty($errors)) {
            $msg = __('Could not validate move data.');
            throw new CustomValidationException($msg, $errors, $this->foldersRelationsTable);
        }
    }

    /**
     * Check if the user can move content out of the folder.
     * - User can always move content from root.
     * - User can always move content out of a personal folder.
     * - User can move content out of a shared folder if the user has at least an update permission on the folder to
     *   move and the original parent folder.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The entity model
     * @param string $foreignId The entity id
     * @param string|null $originalFolderParentId The original folder location. Null if root
     * @return void
     */
    private function assertUserCanMoveOutOfFolder(
        UserAccessControl $uac,
        string $foreignModel,
        string $foreignId,
        ?string $originalFolderParentId = FoldersRelation::ROOT
    ): void {
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
        $userId = $uac->getId();
        $isAllowedToMoveOut = $this->userHasPermissionService->check(
            PermissionsTable::FOLDER_ACO,
            $originalFolderParentId,
            $userId,
            Permission::UPDATE
        );
        if (!$isAllowedToMoveOut) {
            $msg = __('You are not allowed to move this item out of its parent folder.');
            $errors = ['folder_parent_id' => ['has_folder_access' => $msg]];
            $this->handleValidationErrors($errors);
        }

        $isAllowedToMoveContent = $this->userHasPermissionService->check(
            $foreignModel,
            $foreignId,
            $userId,
            Permission::UPDATE
        );
        if (!$isAllowedToMoveContent) {
            $msg = __('You are not allowed to move this item.');
            $errors = ['folder_parent_id' => ['has_access' => $msg]];
            $this->handleValidationErrors($errors);
        }
    }

    /**
     * Check if the user can move content in a folder.
     * - User can always move content to the root.
     * - User can move content with any permission into a personal folder.
     * - User can move content with sufficient permission (>UPDATE) into shared folder with sufficient permission (>UPDATE)
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The item model
     * @param string $foreignId The item id
     * @param string|null $folderParentId The destination folder location
     * @return void
     */
    private function assertUserCanMoveInFolder(
        UserAccessControl $uac,
        string $foreignModel,
        string $foreignId,
        ?string $folderParentId = FoldersRelation::ROOT
    ): void {
        if ($folderParentId === FoldersRelation::ROOT) {
            return;
        }

        $isFolderParentPersonal = $this->foldersRelationsTable->isItemPersonal($folderParentId);
        if ($isFolderParentPersonal) {
            return;
        }

        $isAllowedToMoveIn = $this->userHasPermissionService
            ->check(PermissionsTable::FOLDER_ACO, $folderParentId, $uac->getId(), Permission::UPDATE);
        if (!$isAllowedToMoveIn) {
            $msg = __('You are not allowed to create content into the parent folder.');
            $errors = ['folder_parent_id' => ['has_folder_access' => $msg]];
            $this->handleValidationErrors($errors);
        }

        $isAllowedToMove = $this->userHasPermissionService
            ->check($foreignModel, $foreignId, $uac->getId(), Permission::UPDATE);
        if (!$isAllowedToMove) {
            $msg = __('You are not allowed to move an item in read only into the parent folder.');
            $errors = ['folder_parent_id' => ['has_access' => $msg]];
            $this->handleValidationErrors($errors);
        }
    }

    /**
     * Move an item from a location to another.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $foreignModel The entity model
     * @param string $foreignId The entity id
     * @param string|null $originalFolderParentId The original folder location
     * @param string|null $folderParentId The destination folder location
     * @return void
     */
    private function performMove(
        UserAccessControl $uac,
        string $foreignModel,
        string $foreignId,
        ?string $originalFolderParentId = FoldersRelation::ROOT,
        ?string $folderParentId = FoldersRelation::ROOT
    ): void {
        // If the folder is moved to the root, then only move it for all users having the same representation as the
        // operator.
        if ($folderParentId === FoldersRelation::ROOT) {
            $this->foldersRelationsTable->moveItemFrom($foreignId, [$originalFolderParentId], FoldersRelation::ROOT);
        } else {
            // Otherwise:
            // - Move the target item into the new destination for all users seeing the destination.
            // - Move the target item to the root of all users not seeing the destination folder but having the same
            //   representation as the user above.
            // $users = usersIdsHavingAccessToItemAndDestinationFolder
            $users = $this->foldersRelationsTable
                ->getUsersIdsHavingAccessToMultipleItems([$foreignId, $folderParentId]);
            $conflictedFolderParentIds = $this->foldersRelationsTable
                ->getItemFoldersParentIdsInUsersTrees($users, $foreignId, true);
            if (!empty($conflictedFolderParentIds)) {
                $this->foldersRelationsTable
                    ->moveItemFrom($foreignId, $conflictedFolderParentIds, FoldersRelation::ROOT);
            }
            $this->foldersRelationsTable
                ->moveItemFor($foreignId, $users, $folderParentId);

            if ($foreignModel === FoldersRelation::FOREIGN_MODEL_FOLDER) {
                $this->assertCycleFree($uac);
                $this->detectAndRepairStronglyConnectedComponents($uac, $uac->getId());
            }
        }
    }

    /**
     * Assert that the move doesn't create a cycle in the operator tree.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @return void
     */
    private function assertCycleFree(UserAccessControl $uac): void
    {
        $scc = $this->folderRelationsDetectStronglyConnectedComponents->detectInUserTree($uac->getId());
        if (!empty($scc)) {
            $errors = ['folder_parent_id' => ['cycle' => 'The folder cannot be moved into one of its descendants.']];
            $this->handleValidationErrors($errors);
        }
    }

    /**
     * Look for strongly connected components between the users impacted by a change and other passbolt users.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @param string $userId The target user id the item is added for
     * @return void
     */
    private function detectAndRepairStronglyConnectedComponents(
        UserAccessControl $uac,
        string $userId
    ): void {
        $scc = $this->folderRelationsDetectStronglyConnectedComponents->detectFirstInSharedFolders();
        if (empty($scc)) {
            return;
        }

        // Treat the first scc found.
        $this->foldersRelationsRepairStronglyConnectedComponents->repair($uac, $userId, $scc);

        // Run the repair function again in order to find others SCCs.
        $this->detectAndRepairStronglyConnectedComponents($uac, $userId);
    }
}
