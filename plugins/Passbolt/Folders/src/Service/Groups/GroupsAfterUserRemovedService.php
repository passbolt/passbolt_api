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

namespace Passbolt\Folders\Service\Groups;

use App\Model\Entity\GroupsUser;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\UserHasPermissionService;
use Cake\ORM\TableRegistry;
use Exception;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsRemoveItemFromUserTreeService;

class GroupsAfterUserRemovedService
{
    /**
     * @var FoldersRelationsRemoveItemFromUserTreeService
     */
    private $foldersRelationsRemoveItemFromUserTree;

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
        $this->foldersRelationsRemoveItemFromUserTree = new FoldersRelationsRemoveItemFromUserTreeService();
        $this->userHasPermissionService = new UserHasPermissionService();
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
    }

    /**
     * Handle a user removed from a group.
     *
     * @param GroupsUser $groupUser The deleted group user.
     * @return void
     * @throws Exception
     */
    public function afterUserRemoved(GroupsUser $groupUser)
    {
        $foldersIdsGroupHasAccess = $this->getFoldersIdsGroupHasAccess($groupUser->group_id);
        foreach ($foldersIdsGroupHasAccess as $folderIdGroupHasAccess) {
            $this->removeFolderFromUserTree($folderIdGroupHasAccess, $groupUser->user_id);
        }

        $resourcesIdsGroupHasAccess = $this->getResourcesIdsGroupHasAccess($groupUser->group_id);
        foreach ($resourcesIdsGroupHasAccess as $resourceIdGroupHasAccess) {
            $this->removeResourceFromUserTree($resourceIdGroupHasAccess, $groupUser->user_id);
        }
    }

    /**
     * Retrieve the folders ids a group has access.
     *
     * @param string $groupId The target group
     * @return array The list of resources ids
     */
    private function getFoldersIdsGroupHasAccess(string $groupId)
    {
        return $this->permissionsTable->findAllByAro(PermissionsTable::FOLDER_ACO, $groupId)
            ->select('aco_foreign_key')
            ->extract('aco_foreign_key')
            ->toArray();
    }

    /**
     * Remove a folder from a user tree
     *
     * @param string $folderId The target folder
     * @param string $userId The target user
     * @return void
     * @throws \Exception
     */
    private function removeFolderFromUserTree(string $folderId, string $userId)
    {
        // If the user still has access to the folder, don't alter the user tree.
        $hasAccess = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $folderId, $userId);
        if ($hasAccess) {
            return;
        }

        $this->foldersRelationsRemoveItemFromUserTree->removeItemFromUserTree($folderId, $userId, true);
    }

    /**
     * Retrieve the resources ids a group has access.
     *
     * @param string $groupId The target group
     * @return array The list of resources ids
     */
    private function getResourcesIdsGroupHasAccess(string $groupId)
    {
        return $this->permissionsTable->findAllByAro(PermissionsTable::RESOURCE_ACO, $groupId)
            ->select('aco_foreign_key')
            ->extract('aco_foreign_key')
            ->toArray();
    }

    /**
     * Remove a resource from a user tree
     *
     * @param string $resourceId The target resource
     * @param string $userId The target user
     * @return void
     * @throws \Exception
     */
    private function removeResourceFromUserTree(string $resourceId, string $userId)
    {
        // If the user still has access to the resource, don't alter the user tree.
        $hasAccess = $this->userHasPermissionService->check(PermissionsTable::RESOURCE_ACO, $resourceId, $userId);
        if ($hasAccess) {
            return;
        }

        $this->foldersRelationsRemoveItemFromUserTree->removeItemFromUserTree($resourceId, $userId);
    }
}
