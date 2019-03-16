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
namespace App\Controller\Users;

use App\Controller\AppController;
use App\Controller\Component\QueryStringComponent;
use App\Model\Entity\Role;
use Cake\Event\Event;

class UsersIndexController extends AppController
{

    /**
     * User Index action
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Users');

        $whitelist = [
            'filter' => ['search', 'has-groups', 'has-access', 'is-admin'],
            'order' => [
                'User.username', 'User.created', 'User.modified',
                'Profile.first_name', 'Profile.last_name', 'Profile.created', 'Profile.modified'
            ],
            'contain' => [
                'LastLoggedIn'
            ]
        ];
        if ($this->User->role() === Role::ADMIN) {
            $whitelist['filter'][] = 'is-active';
        }
        $options = $this->QueryString->get($whitelist);
        $users = $this->Users->findIndex($this->User->role(), $options);

        $this->success(__('The operation was successful.'), $users);
    }
}
