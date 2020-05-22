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

namespace Passbolt\Folders\Service\Resources;

use App\Model\Entity\Permission;
use App\Model\Table\GroupsUsersTable;
use App\Model\Table\PermissionsTable;
use App\Model\Table\ResourcesTable;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Exception;
use Passbolt\Folders\Model\Behavior\ContainFolderParentIdBehavior;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemToUserTreeService;

class ResourcesAfterAccessGrantedService
{
    /**
     * @var GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var ResourcesTable
     */
    private $resourcesTable;

    /**
     * @var FoldersRelationsAddItemToUserTreeService
     */
    private $foldersRelationsAddItemToUserTree;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->foldersRelationsAddItemToUserTree = new FoldersRelationsAddItemToUserTreeService();
        $this->resourcesTable = TableRegistry::getTableLocator()->get('Resources');
    }

    /**
     * Handle a granted access on a resource.
     *
     * @param UserAccessControl $uac The operator
     * @param Permission $permission The granted permission
     * @return void
     * @throws Exception
     */
    public function afterAccessGranted(UserAccessControl $uac, Permission $permission)
    {
        $resource = $this->getResource($uac, $permission->aco_foreign_key);

        if ($permission->aro === PermissionsTable::GROUP_ARO) {
            $this->addResourceToGroupUsersTrees($uac, $resource, $permission->aro_foreign_key);
        } else {
            $this->addResourceToUserTree($uac, $resource, $permission->aro_foreign_key);
        }
    }

    /**
     * Retrieve the resource.
     *
     * @param UserAccessControl $uac UserAccessControl updating the resource
     * @param string $resourceId The resource identifier to retrieve.
     * @return \App\Model\Entity\Resource
     * @throws NotFoundException If the resource does not exist.
     */
    private function getResource(UserAccessControl $uac, string $resourceId)
    {
        try {
            return $this->resourcesTable->get($resourceId, [
                'finder' => ContainFolderParentIdBehavior::FINDER_NAME,
                'user_id' => $uac->userId(),
            ]);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The resource does not exist.'));
        }
    }

    /**
     * Add a resource to a group of users trees.
     *
     * @param UserAccessControl $uac The operator
     * @param \App\Model\Entity\Resource $resource The target resource
     * @param string $groupId The target group
     * @return void
     * @throws Exception If something wrong occurred
     */
    private function addResourceToGroupUsersTrees(UserAccessControl $uac, \App\Model\Entity\Resource $resource, string $groupId)
    {
        $grousUsersIds = $this->groupsUsersTable->findByGroupId($groupId)->extract('user_id')->toArray();
        foreach ($grousUsersIds as $groupUserId) {
            $this->addResourceToUserTree($uac, $resource, $groupUserId);
        }
    }

    /**
     * Add a resource to a user tree.
     *
     * @param UserAccessControl $uac The operator
     * @param \App\Model\Entity\Resource $resource The target resource
     * @param string $userId The target user
     * @return void
     * @throws Exception If something wrong occurred
     */
    private function addResourceToUserTree(UserAccessControl $uac, \App\Model\Entity\Resource $resource, string $userId)
    {
        $this->foldersRelationsAddItemToUserTree->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id, $userId);
    }
}
