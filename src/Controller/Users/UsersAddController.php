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
use App\Error\Exception\ValidationRuleException;
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
        $data = $this->_formatRequestData();
        $user = $this->Users->register($data, $this->User->getAccessControl());

        // Handle validation error display
        if (!empty($user->getErrors())) {
            throw new ValidationRuleException(
                __('Could not validate user data.'),
                $user->getErrors(),
                $this->Users
            );
        }

        // Return success response with full user data
        $user = $this->Users->findView($user->id, Role::ADMIN)->first();
        $msg = __('The user was successfully added. This user now need to complete the setup.');
        $this->success($msg, $user);
    }
}
