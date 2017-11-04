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
use App\Utility\Common;
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
    public function add()
    {
        if ($this->User->role() != Role::ADMIN) {
            throw new ForbiddenException();
        }

        $this->loadModel('Groups');

        // Build and validate the entity
        $group = $this->_buildAndValidateGroupEntity();

        // Save the entity
        $result = $this->Groups->save($group);
        $this->_handleValidationError($group);

        $this->success(__('The group has been added successfully.'), $result);
    }

    /**
     * Build the group entity from user input
     *
     * @return \Cake\Datasource\EntityInterface $group group entity
     */
    protected function _buildAndValidateGroupEntity()
    {
        $data = $this->_formatRequestData($this->request->getData());
        $data['created_by'] = Common::uuid('user.id.admin');
        $data['modified_by'] = Common::uuid('user.id.admin');

        // Build entity and perform basic check
        $group = $this->Groups->newEntity($data, [
            'validate' => 'default',
            'accessibleFields' => [
                'name' => true,
                'created_by' => true,
                'modified_by' => true,
                'groups_users' => true
            ]
        ]);

        // Handle validation errors if any at this stage.
        $this->_handleValidationError($group);

        return $group;
    }

    /**
     * Format request data formatted for API v1 to API v2 format
     *
     * @param array $data
     * @return array
     */
    protected function _formatRequestData($data = [])
    {
        $output['name'] = Hash::get($data, 'Group.name');
        $output['groups_users'] = Hash::reduce($data, 'GroupUsers.{n}', function ($result, $row) {
            $result[] = [
                'user_id' => Hash::get($row, 'GroupUser.user_id', ''),
                'is_admin' => (boolean)Hash::get($row, 'GroupUser.is_admin', false)
            ];

            return $result;
        }, []);

        return $output;
    }

    /**
     * Manage validation errors.
     *
     * @param \Cake\Datasource\EntityInterface $group Group
     * @return bool
     */
    protected function _handleValidationError($group)
    {
        $errors = $group->getErrors();
        if (!empty($errors)) {
            $output = $this->_formatErrorData($errors);
            throw new ValidationRuleException(__('Could not validate group data.'), $output);
        }
    }

    /**
     * Format errors data
     *
     * @param array $errors
     * @return array
     */
    protected function _formatErrorData($errors = [])
    {
        // @TODO white list errors (maybe we don't want some business rule to be communicated : soft deleted by instance, has access)
        // @TODO reformat for API v1, find an elegant way to do it.
        // Looking at the error handler in the App JS, only the group name is treated.
        // @see PASSBOLT_API_REPO::app/webroot/js/app/component/group_edit.js:493
        $output = [];
        if (isset($errors['name']) && isset($errors)) {
            // Only the values of the error are returned, not the rules names.
            $output['Group']['name'] = array_values($errors['name']);
        }

        return $output;
    }
}
