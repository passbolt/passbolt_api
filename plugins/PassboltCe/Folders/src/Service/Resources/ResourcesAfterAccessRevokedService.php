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

namespace Passbolt\Folders\Service\Resources;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\UserHasPermissionService;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Folders\Model\Behavior\FolderizableBehavior;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsRemoveItemFromUserTreeService;

class ResourcesAfterAccessRevokedService
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsRemoveItemFromUserTreeService
     */
    private $foldersRelationsRemoveItemFromUserTree;

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $GroupsUsers;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    private $Resources;

    /**
     * @var \App\Service\Permissions\UserHasPermissionService
     */
    private $userHasPermissionService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->GroupsUsers = $this->fetchTable('GroupsUsers');
        /** @phpstan-ignore-next-line */
        $this->Resources = $this->fetchTable('Resources');
        $this->foldersRelationsRemoveItemFromUserTree = new FoldersRelationsRemoveItemFromUserTreeService();
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     * Handle a revoked access on a resource.
     *
     * @param \App\Utility\UserAccessControl $uac The current user.
     * @param \App\Model\Entity\Permission $permission The revoked permission.
     * @return void
     * @throws \Exception
     */
    public function afterAccessRevoked(UserAccessControl $uac, Permission $permission)
    {
        $resource = $this->getResource($uac, $permission->aco_foreign_key);

        if ($permission->aro === PermissionsTable::GROUP_ARO) {
            $this->removeResourceFromGroupUsersTrees($resource, $permission->aro_foreign_key);
        } else {
            $this->removeResourceFromUserTree($resource, $permission->aro_foreign_key);
        }
    }

    /**
     * Retrieve the resource.
     *
     * @param \App\Utility\UserAccessControl $uac UserAccessControl updating the resource
     * @param string $resourceId The resource identifier to retrieve.
     * @return \App\Model\Entity\Resource
     * @throws \Cake\Http\Exception\NotFoundException If the resource does not exist.
     */
    private function getResource(UserAccessControl $uac, string $resourceId)
    {
        try {
            return $this->Resources->get($resourceId, [
                'finder' => FolderizableBehavior::FINDER_NAME,
                'user_id' => $uac->getId(),
            ]);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The resource does not exist.'));
        }
    }

    /**
     * Remove a resource from a group of users trees.
     *
     * @param \App\Model\Entity\Resource $resource The target resource
     * @param string $groupId The target group
     * @return void
     * @throws \Exception
     */
    private function removeResourceFromGroupUsersTrees(\App\Model\Entity\Resource $resource, string $groupId)
    {
        $grousUsersIds = $this->GroupsUsers->findByGroupId($groupId)
            ->all()
            ->extract('user_id')
            ->toArray();
        foreach ($grousUsersIds as $groupUserId) {
            $this->removeResourceFromUserTree($resource, $groupUserId);
        }
    }

    /**
     * Remove a resource from a user tree
     *
     * @param \App\Model\Entity\Resource $resource The target resource
     * @param string $userId The target user
     * @return void
     * @throws \Exception
     */
    private function removeResourceFromUserTree(\App\Model\Entity\Resource $resource, string $userId)
    {
        // If the user still has access to the resource, don't alter the user tree.
        $hasAccess = $this->userHasPermissionService->check(PermissionsTable::RESOURCE_ACO, $resource->id, $userId);
        if ($hasAccess) {
            return;
        }

        $this->foldersRelationsRemoveItemFromUserTree->removeItemFromUserTree($resource->id, $userId);
    }
}
