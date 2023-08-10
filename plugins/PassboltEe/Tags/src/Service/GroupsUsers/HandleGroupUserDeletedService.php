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
 * @since         3.7.0
 */

namespace Passbolt\Tags\Service\GroupsUsers;

use App\Model\Entity\GroupsUser;
use App\Model\Table\PermissionsTable;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class HandleGroupUserDeletedService
{
    /**
     * @var \Passbolt\Tags\Model\Table\TagsTable
     */
    private $tagsTable;

    /**
     * @var \Passbolt\Tags\Model\Table\ResourcesTagsTable
     */
    private $resourcesTagsTable;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    private $permissionsTable;

    /**
     * GroupUserDeletedService constructor.
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->tagsTable = TableRegistry::getTableLocator()->get('Passbolt/Tags.Tags');
        /** @phpstan-ignore-next-line */
        $this->resourcesTagsTable = TableRegistry::getTableLocator()->get('Passbolt/Tags.ResourcesTags');
        /** @phpstan-ignore-next-line */
        $this->permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
    }

    /**
     * Handle group user deleted event.
     *
     * @note It doesn't trigger after delete events on ResourcesTags model.
     * @param \App\Model\Entity\GroupsUser $groupUser The deleted group user.
     * @return void
     * @throws \Exception
     */
    public function handle(GroupsUser $groupUser)
    {
        $this->resourcesTagsTable->deleteAll([
            'user_id' => $groupUser->user_id,
            'resource_id IN' => $this->findLostAccessResourcesIdsQuery($groupUser),
        ]);
        $this->tagsTable->deleteAllUnusedTags();
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
}
