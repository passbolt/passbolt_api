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

namespace Passbolt\Folders\Service\GroupsUsers;

use App\Model\Entity\GroupsUser;
use App\Model\Table\PermissionsTable;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Passbolt\Folders\Model\Entity\FoldersRelation;

class HandleGroupUserDeletedService
{
    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $permissionsTable;

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        /** @phpstan-ignore-next-line */
        $this->foldersRelationsTable = TableRegistry::getTableLocator()->get('FoldersRelations');
    }

    /**
     * Handle a group user deletion.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The deleted group user.
     * @return void
     * @throws \Exception
     */
    public function handle(GroupsUser $groupUser)
    {
        $this->removeLostAccessesResourcesFromUserTree($groupUser);
        $this->removeLostAccessesFoldersFromUserTree($groupUser);
    }

    /**
     * Remove resources the user lost access from its tree.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser $groupUser The deleted group user.
     * @return void
     */
    private function removeLostAccessesResourcesFromUserTree(GroupsUser $groupUser): void
    {
        $this->foldersRelationsTable->deleteAll([
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_RESOURCE,
            'user_id' => $groupUser->user_id,
            'foreign_id IN' => $this->findLostAccessResourcesIdsQuery($groupUser),
        ]);
    }

    /**
     * Remove folders the user lost access from its tree.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser $groupUser The deleted group user.
     * @return void
     */
    private function removeLostAccessesFoldersFromUserTree(GroupsUser $groupUser): void
    {
        $this->foldersRelationsTable->deleteAll([
            'foreign_model' => FoldersRelation::FOREIGN_MODEL_FOLDER,
            'user_id' => $groupUser->user_id,
            'foreign_id IN' => $this->findLostAccessFoldersIdsQuery($groupUser),
        ]);
        $this->moveToRootLostAccessFoldersContent($groupUser);
    }

    /**
     * Move folders the user lost access content to the user tree root.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser $groupUser The deleted group user.
     * @return void
     */
    private function moveToRootLostAccessFoldersContent(GroupsUser $groupUser): void
    {
        $this->foldersRelationsTable->updateAll(['folder_parent_id' => null], [
            'user_id' => $groupUser->user_id,
            'folder_parent_id IN' => $this->findLostAccessFoldersIdsQuery($groupUser),
        ]);
    }

    /**
     * Find the lost access resources ids.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to delete.
     * @return \Cake\ORM\Query
     */
    private function findLostAccessResourcesIdsQuery(GroupsUser $groupUser): Query
    {
        return $this->permissionsTable->findAcosAccessesDiffBetweenGroupAndUser(
            PermissionsTable::RESOURCE_ACO,
            $groupUser->group_id,
            $groupUser->user_id,
        );
    }

    /**
     * Find the lost access folders ids.
     *
     * @param \App\Model\Entity\GroupsUser $groupUser The group user to delete.
     * @return \Cake\ORM\Query
     */
    private function findLostAccessFoldersIdsQuery(GroupsUser $groupUser): Query
    {
        return $this->permissionsTable->findAcosAccessesDiffBetweenGroupAndUser(
            PermissionsTable::FOLDER_ACO,
            $groupUser->group_id,
            $groupUser->user_id,
        );
    }
}
