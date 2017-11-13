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
namespace App\Controller\Users;

use App\Controller\AppController;
use App\Model\Entity\Role;
use Cake\Event\Event;
use Cake\Network\Exception\ForbiddenException;

class UsersAddController extends UsersRegisterController
{
    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->loadModel('Users');
        $this->loadModel('AuthenticationTokens');
        // To not use userRegisterController beforeFilter rules
        return AppController::beforeFilter($event);
    }

    /**
     * User add action (admin only)
     *
     * @return void
     */
    public function addPost()
    {
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('Only administrators can add new users.'));
        }
        $user = $this->_registerUser();
        $this->set('user', $user);
        $this->success(__('The user was successfully added. This user now need to complete the setup.'));
    }

    /**
     * Notify the user
     *
     * @param object $user User entity
     * @param object $token Token entity
     */
    protected function _notifyUser($user, $token)
    {
        $admin = $this->Users->getForEmailContext($this->User->id());
        $event = new Event('UsersAddController.addPost.success', $this, [
            'user' => $user, 'token' => $token, 'admin' => $admin
        ]);
        $this->getEventManager()->dispatch($event);
    }
}
