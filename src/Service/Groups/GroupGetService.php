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

namespace App\Service\Groups;

use App\Model\Entity\Group;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

/**
 * Class GroupGetService
 *
 * @package App\Service\Groups
 * @property \App\Model\Table\GroupsTable $groupsTable
 */
class GroupGetService
{
    /**
     * @var \App\Model\Table\GroupsTable
     */
    private $groupsTable;

    /**
     * GroupGetService constructor
     */
    public function __construct()
    {
        $this->groupsTable = TableRegistry::getTableLocator()->get('Groups');
    }

    /**
     * Get a group by ID or throw relevant HTTP exception.
     *
     * @param string $groupId The identifier of the group to get
     * @return \App\Model\Entity\Group
     * @throws \Cake\Http\Exception\BadRequestException If the group identifier is not a valid UUID.
     * @throws \Cake\Http\Exception\NotFoundException If the group does not exist.
     */
    protected function getOrFail(string $groupId): Group
    {
        if (!Validation::uuid($groupId)) {
            throw new BadRequestException(__('The group identifier should be a valid UUID.'));
        }

        try {
            $group = $this->groupsTable->get($groupId);
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The group does not exist.'));
        }

        return $group;
    }

    /**
     * Get a group by ID or throw relevant HTTP exception.
     *
     * @param string $groupId The identifier of the group to get
     * @return \App\Model\Entity\Group
     * @throws \Cake\Http\Exception\NotFoundException If the group does not exist or is soft deleted
     */
    public function getNotDeletedOrFail(string $groupId): Group
    {
        $group = $this->getOrFail($groupId);
        if ($group->deleted) {
            throw new NotFoundException(__('The group does not exist.'));
        }

        return $group;
    }
}
