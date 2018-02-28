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
namespace Passbolt\WebInstaller\Controller;

use Cake\Controller\Controller;
use Cake\Controller\Component\FlashComponent;
use App\Model\Entity\Role;

class AccountCreationController extends Controller
{
    /**
     * Index
     */
    function index() {
        if(!empty($this->request->getData())) {
            $user = $this->_createUser($this->request->getData());
            if ($user !== false) {
                $this->_createToken($user->id);
                return $this->redirect('install/complete');
            }
        }

        $this->render('Pages/account_creation');
    }

    /**
     * Complete installation
     */
    function complete() {
        $session = $this->request->getSession();
        $token = $session->read('Passbolt.Config.user.token');
        $this->set(['token' => $token]);
        $this->render('Pages/complete');
    }

    /**
     * Create Authentication token.
     * @param $userId
     * @return mixed
     */
    private function _createToken($userId) {
        $this->loadModel('AuthenticationTokens');
        $token = $this->AuthenticationTokens->generate($userId);

        $session = $this->request->getSession();
        $session->write('Passbolt.Config.user.token', $token);

        return $token;
    }

    /**
     * Create user.
     * @param $data
     * @return bool
     */
    private function _createUser($data) {
        $this->loadModel('Users');
        $data['deleted'] = false;

        $user = $this->Users->buildEntity($data, Role::ADMIN);
        if ($user->getErrors()) {
            $this->set('user', $user);
            return false;
        }

        $result = $this->Users->save($user, ['checkRules' => true, 'atomic' => false]);
        if ($user->getErrors()) {
            $this->set('user', $user);
            return false;
        }

        if($result == false) {
            $this->Flash->error(__('Could not save the user account'));
            return false;
        }

        return $user;
    }
}