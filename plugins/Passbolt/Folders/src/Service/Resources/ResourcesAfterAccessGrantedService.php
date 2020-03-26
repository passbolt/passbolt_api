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

namespace Passbolt\Folders\Service\Resources;

use App\Model\Entity\Permission;
use App\Model\Table\GroupsUsersTable;
use App\Model\Table\PermissionsTable;
use App\Model\Table\ResourcesTable;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Exception;
use Passbolt\Folders\Model\Behavior\ContainFolderParentIdBehavior;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Model\Table\FoldersRelationsTable;
use Passbolt\Folders\Model\Table\FoldersTable;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsCreateService;

class ResourcesAfterAccessGrantedService
{
    /**
     * @var GroupsUsersTable
     */
    private $groupsUsersTable;

    /**
     * @var FoldersRelationsCreateService
     */
    private $foldersRelationsCreateService;

    /**
     * @var FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * @var FoldersTable
     */
    private $foldersTable;

    /**
     * @var ResourcesTable
     */
    private $resourcesTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->foldersRelationsCreateService = new FoldersRelationsCreateService();
        $this->foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.FoldersRelations');
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
        $existsInUserTree = $this->foldersRelationsTable->existsInUserTree($userId, $resource->id);
        if ($existsInUserTree) {
            return;
        }

        // Insert the resource at the root of the target user tree.
        $this->foldersRelationsCreateService->create($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resource->id, $userId, null);

        // When an existing resource is shared with a user, the resource is added to the user tree. In order to ensure a
        // common organization among all passbolt users, the user tree will be reconstructed based on other trees.
        // A highest priority is given to the operator representation, so the algorithm will first apply the operator
        // view (if any), and then will apply other users representations (if any).
        $appliedFolderParentId = $this->reconstructFolderParentFromOperatorTree($resource, $userId);
        if (is_null($appliedFolderParentId)) {
            $this->reconstructFolderParentFromOtherUsersTrees($resource, $userId);
        }
    }

    /**
     * Reconstruct a folder parent in a user tree based on the operator representation
     *
     * @param \App\Model\Entity\Resource $resource The target resource
     * @param string $userId The target user
     * @return string|null
     */
    private function reconstructFolderParentFromOperatorTree(\App\Model\Entity\Resource $resource, string $userId)
    {
        // The resource doesn't have any parent in the operator tree.
        if (is_null($resource->folder_parent_id)) {
            return null;
        }

        // The user cannot see the operator folder parent.
        $existsInUserTree = $this->foldersRelationsTable->existsInUserTree($userId, $resource->folder_parent_id);
        if (!$existsInUserTree) {
            return null;
        }

        // Conflict detection: Another folder in the target user tree could be a parent of the target resource.
        // The operator representation has the highest priority and it will be applied: the target resource will be moved
        // from the identified other parent folders to the root of the users having this representation.
        $conflictedFolderParentsIds = $this->getPotentialFolderParentsIds($resource->id, $userId, ['exclude' => $resource->folder_parent_id]);
        if (!empty($conflictedFolderParentsIds)) {
            $this->foldersRelationsTable->moveItemFrom($resource->id, $conflictedFolderParentsIds);
        }

        // Move the resource into the parent folder.
        $this->foldersRelationsTable->moveItemFor($resource->id, [$userId], $resource->folder_parent_id);

        return $resource->folder_parent_id;
    }

    /**
     * Get the potential folder parents of a resource in a user tree.
     *
     * @param string $resourceId The target resource
     * @param string $userId The target user
     * @param array $options Option
     * [
     *   array $exclude Exclude folders from the query
     * ]
     * @return array
     */
    private function getPotentialFolderParentsIds(string $resourceId, string $userId, array $options = [])
    {
        $userItems = $this->foldersRelationsTable->findByUserIdAndForeignModel($userId, FoldersRelation::FOREIGN_MODEL_FOLDER);

        $query = $this->foldersRelationsTable->find()
            ->where([
                'foreign_id' => $resourceId,
                'folder_parent_id IN' => $userItems->select('foreign_id'),
            ]);

        $exclude = Hash::get($options, 'exclude', []);
        if (!empty($exclude)) {
            $query->where([
                'folder_parent_id NOT IN' => $exclude,
            ]);
        }

        return $query->select('folder_parent_id')
            ->distinct()
            ->extract('folder_parent_id')
            ->toArray();
    }

    /**
     * Reconstruct the parent of a resource in a user tree based on non operator users representation
     * @param \App\Model\Entity\Resource $resource The target resource
     * @param string $userId The target user
     * @return void
     */
    private function reconstructFolderParentFromOtherUsersTrees(\App\Model\Entity\Resource $resource, string $userId)
    {
        $folderParentsIds = $this->getPotentialFolderParentsIds($resource->id, $userId);
        $folderParentsIdsCount = count($folderParentsIds);

        if (!$folderParentsIdsCount) {
            return;
        } elseif ($folderParentsIdsCount > 1) {
            // Conflict detection: Multiple folder parents.
            // We don't choose a version over another, the folder will be from the identified other parent folders to
            // the root.
            $this->foldersRelationsTable->moveItemFrom($resource->id, $folderParentsIds, null);

            return;
        } else {
            $folderParentId = $folderParentsIds[0];
        }

        // Move the folder into the parent parent.
        $this->foldersRelationsTable->moveItemFor($resource->id, [$userId], $folderParentId);
    }
}
