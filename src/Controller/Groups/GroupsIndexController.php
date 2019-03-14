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

class GroupsIndexController extends AppController
{
    /**
     * Group Index action
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Groups');

        // Retrieve and sanity the query options.
        $whitelist = [
            'contain' => ['modifier', 'modifier.profile', 'user', 'group_user', 'my_group_user'],
            'filter' => ['has-users', 'has-managers'],
            'order' => ['Group.name']
        ];
        $options = $this->QueryString->get($whitelist);
        if (isset($options['contain']['my_group_user'])) {
            $options['my_user_id'] = $this->User->id();
        }

        // Retrieve the groups.
        $groups = $this->Groups->findIndex($options);
        $this->success(__('The operation was successful.'), $groups);
    }
}
