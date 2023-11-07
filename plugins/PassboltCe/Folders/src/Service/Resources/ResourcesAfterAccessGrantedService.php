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
use App\Model\Entity\Resource;
use App\Model\Table\PermissionsTable;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Behavior\FolderizableBehavior;
use Passbolt\Folders\Model\Dto\FolderRelationDto;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemsToUserTreeService;

class ResourcesAfterAccessGrantedService
{
    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    private $resourcesTable;

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemsToUserTreeService
     */
    private $foldersRelationsAddItemsToUserTree;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->resourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');

        $this->foldersRelationsAddItemsToUserTree = new FoldersRelationsAddItemsToUserTreeService();
    }

    /**
     * Handle a granted access on a resource.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \App\Model\Entity\Permission $permission The granted permission
     * @return void
     * @throws \Exception
     */
    public function afterAccessGranted(UserAccessControl $uac, Permission $permission): void
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
     * @param \App\Utility\UserAccessControl $uac UserAccessControl updating the resource
     * @param string $resourceId The resource identifier to retrieve.
     * @return \App\Model\Entity\Resource
     * @throws \Cake\Http\Exception\NotFoundException If the resource does not exist.
     */
    private function getResource(UserAccessControl $uac, string $resourceId): Resource
    {
        try {
            return $this->resourcesTable->get($resourceId, [
                'finder' => FolderizableBehavior::FINDER_NAME,
                'user_id' => $uac->getId(),
            ]);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The resource does not exist.'));
        }
    }

    /**
     * Add a resource to a group of users trees.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \App\Model\Entity\Resource $resource The target resource
     * @param string $groupId The target group
     * @return void
     * @throws \Exception If something wrong occurred
     */
    private function addResourceToGroupUsersTrees(UserAccessControl $uac, Resource $resource, string $groupId): void
    {
        $groupUsersIds = $this->findGroupsUsersIdsNotHavingFolderRelation($resource, $groupId);
        foreach ($groupUsersIds as $groupUserId) {
            $this->addResourceToUserTree($uac, $resource, $groupUserId);
        }
    }

    /**
     * Find the group users not having access to the
     *
     * @param \App\Model\Entity\Resource $resource The resource to search for
     * @param string $groupId The group identifier to search for
     * @return array<string> An array of user identifiers
     */
    private function findGroupsUsersIdsNotHavingFolderRelation(Resource $resource, string $groupId): array
    {
        $usersHavingFolderRelationQuery = $this->foldersRelationsTable->find()
            ->select('user_id')
            ->where([
                'foreign_id' => $resource->id,
            ]);

        return $this->groupsUsersTable->find()
            ->where([
                'group_id' => $groupId,
                'user_id NOT IN' => $usersHavingFolderRelationQuery,
            ])
            ->all()
            ->extract('user_id')
            ->toArray();
    }

    /**
     * Add a resource to a user tree.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \App\Model\Entity\Resource $resource The target resource
     * @param string $userId The target user
     * @return void
     * @throws \Exception If something wrong occurred
     */
    private function addResourceToUserTree(UserAccessControl $uac, Resource $resource, string $userId): void
    {
        $isResourceInUserTree = $this->foldersRelationsTable->isItemInUserTree(
            $userId,
            $resource->id,
            FoldersRelation::FOREIGN_MODEL_RESOURCE
        );

        if ($isResourceInUserTree) {
            return;
        }

        $items = [new FolderRelationDto(FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id)];
        $this->foldersRelationsAddItemsToUserTree->addItemsToUserTree($uac, $userId, $items);
    }
}
