<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Controller\Groups;

use App\Controller\AppController;
use App\Error\Exception\ValidationRuleException;
use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use Cake\Event\Event;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Utility\Hash;

class GroupsAddController extends AppController
{
    /**
     * Group Add action
     *
     * @throws InternalErrorException If an unexpected error occurred when saving the group
     * @throws ForbiddenException If the user is not an admin
     * @return void
     */
    public function addPost()
    {
        if (!$this->User->isAdmin()) {
            throw new ForbiddenException();
        }

        $this->loadModel('Groups');

        // Build and validate the entity
        $group = $this->_buildAndValidateGroupEntity();

        // Save the entity
        $result = $this->Groups->save($group);
        if (!$result) {
            $this->_handleValidationError($group);
            throw new InternalErrorException(__('Could not add the group. Please try again later'));
        }
        $this->_notifyUsers($group);

        $this->success(__('The group has been added successfully.'), $result);
    }

    /**
     * Build the group entity from user input
     *
     * @return \Cake\Datasource\EntityInterface $group group entity
     */
    protected function _buildAndValidateGroupEntity()
    {
        $data = $this->_formatRequestData();
        $data['created_by'] = $this->User->id();
        $data['modified_by'] = $this->User->id();
        $data['deleted'] = false;

        // Build entity and perform basic check
        $group = $this->Groups->newEntity($data, [
            'accessibleFields' => [
                'name' => true,
                'created_by' => true,
                'modified_by' => true,
                'groups_users' => true,
                'deleted' => true
            ],
            'associated' => [
                'GroupsUsers' => [
                    'validate' => 'saveGroup',
                    'accessibleFields' => [
                        'user_id' => true,
                        'is_admin' => true
                    ]
                ],
            ]
        ]);

        // Handle validation errors if any at this stage.
        $this->_handleValidationError($group);

        return $group;
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

    /**
     * Manage validation errors.
     *
     * @param \Cake\Datasource\EntityInterface $group Group
     * @return void
     */
    protected function _handleValidationError($group)
    {
        $errors = $group->getErrors();
        if (!empty($errors)) {
            throw new ValidationRuleException(__('Could not validate group data.'), $errors, $this->Groups);
        }
    }

    /**
     * Notify the users they have been added to the group
     *
     * @param \App\Model\Entity\Group $group Goup
     * @return void
     */
    protected function _notifyUsers($group)
    {
        $event = new Event('GroupsAddController.addPost.success', $this, [
            'group' => $group
        ]);
        $this->getEventManager()->dispatch($event);
    }
}
