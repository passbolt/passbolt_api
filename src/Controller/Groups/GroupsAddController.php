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
 * @since         2.0.0
 */

namespace App\Controller\Groups;

use App\Controller\AppController;
use Cake\Http\Exception\ForbiddenException;
use Cake\Utility\Hash;

/**
 * GroupsAddController Class
 */
class GroupsAddController extends AppController
{
    /**
     * Group Add action
     *
     * @throws \Cake\Http\Exception\InternalErrorException If an unexpected error occurred when saving the group
     * @throws \Cake\Http\Exception\ForbiddenException If the user is not an admin
     * @throws \App\Error\Exception\ValidationException if the group validation failed
     * @throws \Cake\Http\Exception\InternalErrorException if the group can't be saved for some reason
     * @return void
     */
    public function addPost()
    {
        $this->assertJson();

        if (!$this->User->isAdmin()) {
            throw new ForbiddenException();
        }

        $data = $this->_formatRequestData();
        /** @var \App\Model\Table\GroupsTable $GroupsTable */
        $GroupsTable = $this->fetchTable('Groups');
        $group = $GroupsTable->create($data, $this->User->getAccessControl());

        $msg = __('The group has been added successfully.');
        $this->success($msg, $group);
    }

    /**
     * Format request data formatted for API v1
     *
     * Note: historically broken in v2.14 and before
     * Prior to v3 this method expected data in v1 format only
     * So both v2 and v1 format are supported in v2 & v3
     *
     * @deprecated when support for API v2 is dropped
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
