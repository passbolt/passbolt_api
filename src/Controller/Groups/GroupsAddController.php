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
 * @since         2.0.0
 */

namespace App\Controller\Groups;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Table\GroupsTable;
use App\Model\Table\UsersTable;
use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsAddController extends AppController
{
    /**  @var GroupsTable */
    public $Groups;

    /** @var UsersTable */
    public $Users;

    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->Users = TableRegistry::getTableLocator()->get('Users');

        return parent::beforeFilter($event);
    }

    /**
     * Group Add action
     *
     * @throws InternalErrorException If an unexpected error occurred when saving the group
     * @throws ForbiddenException If the user is not an admin
     * @throws ValidationException if the group validation failed
     * @throws InternalErrorException if the group can't be saved for some reason
     * @return void
     */
    public function addPost()
    {
        if (!$this->User->isAdmin()) {
            throw new ForbiddenException();
        }

        $data = $this->_formatRequestData();
        $group = $this->Groups->create($data, $this->User->getAccessControl());

        $msg = __('The group has been added successfully.');
        $this->success($msg, $group);
    }

    /**
     * Format request data formatted for API v1
     *
     * Note: historically broken in v2.14 and before
     * Prior to v3 this method expected data in v1 format only
     * So both v2 and v1 format are supported in v2
     *
     * @return array
     */
    protected function _formatRequestData()
    {
        $data = $this->request->getData();
        $output['name'] = $data['name'] ?? Hash::get($data, 'Group.name');
        if (isset($data['groups_users'])) {
            $output['groups_users'] = $data['groups_users'];
        } elseif (isset($data['GroupUsers'])) {
            $output['groups_users'] = Hash::reduce($data, 'GroupUsers.{n}', function ($result, $row) {
                $result[] = [
                    'user_id' => Hash::get($row, 'GroupUser.user_id', ''),
                    'is_admin' => (bool)Hash::get($row, 'GroupUser.is_admin', false),
                ];

                return $result;
            });
        }

        return $output;
    }
}
