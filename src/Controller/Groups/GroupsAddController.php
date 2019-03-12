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
use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Utility\Hash;

class GroupsAddController extends AppController
{
    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->loadModel('Groups');
        $this->loadModel('Users');

        return AppController::beforeFilter($event);
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
     * Format request data formatted for API v1 to API v2 format
     *
     * @return array
     */
    protected function _formatRequestData()
    {
        $data = $this->request->getData();
        $output['name'] = Hash::get($data, 'Group.name');
        if (isset($data['GroupUsers'])) {
            $output['groups_users'] = Hash::reduce($data, 'GroupUsers.{n}', function ($result, $row) {
                $result[] = [
                    'user_id' => Hash::get($row, 'GroupUser.user_id', ''),
                    'is_admin' => (bool)Hash::get($row, 'GroupUser.is_admin', false)
                ];

                return $result;
            }, []);
        }

        return $output;
    }
}
