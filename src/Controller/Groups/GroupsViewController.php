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
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

/**
 * @property \App\Model\Table\GroupsTable $Groups
 */
class GroupsViewController extends AppController
{
    /**
     * Group View action
     *
     * @throws \Cake\Http\Exception\BadRequestException if the group id is not a uuid
     * @throws \Cake\Http\Exception\NotFoundException if the group does not exist
     * @param string $id uuid Identifier of the group
     * @return void
     */
    public function view(string $id)
    {
        $this->assertJson();

        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The group id is not valid.'));
        }
        /** @var \App\Model\Table\GroupsTable $groupsTable */
        $groupsTable = $this->fetchTable('Groups');

        // Retrieve and sanity the query options.
        $whitelist = [
            'contain' => [
                'modifier', 'modifier.profile', 'my_group_user',
                'users', 'groups_users', 'groups_users.user',
                'groups_users.user.profile', 'groups_users.user.gpgkey',
                // Deprecated contains, use plural form instead
                // @deprecated remove when v2 support is dropped
                'user', 'group_user', 'group_user.user', 'group_user.user.profile',
                'group_user.user.gpgkey',
            ],
        ];
        $options = $this->QueryString->get($whitelist);
        if (isset($options['contain']['my_group_user'])) {
            $options['my_user_id'] = $this->User->id();
        }

        // Retrieve the group.
        $group = $groupsTable->findView($id, $options)->first();
        if (empty($group)) {
            throw new NotFoundException(__('The group does not exist.'));
        }

        $this->success(__('The operation was successful.'), $group);
    }
}
