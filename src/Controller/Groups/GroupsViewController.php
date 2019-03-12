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
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

class GroupsViewController extends AppController
{
    /**
     * Group View action
     *
     * @throws BadRequestException if the group id is not a uuid
     * @throws NotFoundException if the group does not exist
     * @param string $id uuid Identifier of the group
     * @return void
     */
    public function view($id)
    {
        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The group id is not valid.'));
        }
        $this->loadModel('Groups');

        // Retrieve and sanity the query options.
        $whitelist = [
            'contain' => ['modifier', 'modifier.profile', 'user', 'group_user', 'group_user.user', 'group_user.user.profile', 'group_user.user.gpgkey', 'my_group_user']
        ];
        $options = $this->QueryString->get($whitelist);
        if (isset($options['contain']['my_group_user'])) {
            $options['my_user_id'] = $this->User->id();
        }

        // Default v1 options.
        $defaultV1Options = [
            'contain' => ['group_user' => 1, 'group_user.user' => 1, 'group_user.user.profile' => 1, 'group_user.user.gpgkey' => 1]
        ];
        $options = array_merge_recursive($options, $defaultV1Options);

        // Retrieve the group.
        $group = $this->Groups->findView($id, $options)->first();
        if (empty($group)) {
            throw new NotFoundException(__('The group does not exist.'));
        }

        $this->success(__('The operation was successful.'), $group);
    }
}
