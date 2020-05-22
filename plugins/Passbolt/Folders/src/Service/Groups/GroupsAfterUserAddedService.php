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
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Exception;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsAddItemToUserTreeService;

class GroupsAfterUserAddedService
{
    /**
     * @var FoldersRelationsAddItemToUserTreeService
     */
    private $foldersRelationsAddItemFromUserTree;

    /**
     * @var PermissionsTable
     */
    private $permissionsTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->foldersRelationsAddItemFromUserTree = new FoldersRelationsAddItemToUserTreeService();
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
    }

    /**
     * Handle a user added to a group.
     *
     * @param UserAccessControl $uac The current user
     * @param GroupsUser $groupUser The added group user.
     * @return void
     * @throws Exception If something unexpected occurred
     */
    public function afterUserAdded(UserAccessControl $uac, GroupsUser $groupUser)
    {
        $foldersIdsGroupHasAccess = $this->getFoldersIdsGroupHasAccess($groupUser->group_id);
        foreach ($foldersIdsGroupHasAccess as $folderIdGroupHasAccess) {
            $this->foldersRelationsAddItemFromUserTree->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_FOLDER, $folderIdGroupHasAccess, $groupUser->user_id);
        }

        $resourcesIdsGroupHasAccess = $this->getResourcesIdsGroupHasAccess($groupUser->group_id);
        foreach ($resourcesIdsGroupHasAccess as $resourceIdGroupHasAccess) {
            $this->foldersRelationsAddItemFromUserTree->addItemToUserTree($uac, FoldersRelation::FOREIGN_MODEL_RESOURCE, $resourceIdGroupHasAccess, $groupUser->user_id);
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
}
