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
        $user = $this->Users->findView($user->id, Role::ADMIN)->first();
        $msg = __('The user was successfully added. This user now need to complete the setup.');
        $this->success($msg, $user);
    }

    /**
     * Notify the user
     *
     * @param object $user User entity
     * @param object $token Token entity
     * @return void
     */
    protected function _notifyUser($user, $token)
    {
        $event = new Event('UsersAddController.addPost.success', $this, [
            'user' => $user, 'token' => $token, 'adminId' => $this->User->id()
        ]);
        $this->getEventManager()->dispatch($event);
    }
}
