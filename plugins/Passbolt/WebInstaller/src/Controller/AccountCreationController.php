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

use App\Model\Entity\Role;
use Cake\Network\Exception\ForbiddenException;

class AccountCreationController extends WebInstallerController
{
    /**
     * Initialize.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->stepInfo['next'] = 'install/complete';
        $this->stepInfo['template'] = 'Pages/account_creation';
    }

    /**
     * Index
     * @return mixed
     */
    public function index()
    {
        // Make sure that the user is allowed to access this section.
        $this->_checkIsAllowed();

        if (!empty($this->request->getData())) {
            $data = $this->request->getData();
            $this->loadModel('Roles');
            $user = $this->_createUser($this->request->getData());
            if ($user !== false) {
                $this->_createToken($user->id);

                return $this->_success();
            }
        }

        $this->render('Pages/account_creation');
    }

    /**
     * Create Authentication token.
     * @param string $userId uuid of user
     * @return mixed
     */
    protected function _createToken($userId)
    {
        $this->loadModel('AuthenticationTokens');
        $token = $this->AuthenticationTokens->generate($userId);

        $session = $this->request->getSession();
        $session->write(self::CONFIG_KEY . '.user.token', $token);

        return $token;
    }

    /**
     * Create user.
     * @param array $data data provided by request
     * @return bool
     */
    protected function _createUser($data)
    {
        $this->loadModel('Users');
        $data['deleted'] = false;
        $data['role_id'] = $this->Roles->getIdByName(Role::ADMIN);

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

        if ($result == false) {
            $this->Flash->error(__('Could not save the user account'));

            return false;
        }

        return $user;
    }

    /**
     * Check if this form is accessible.
     * We do not want anyone to be able to create new users once passbolt is already installed.
     * @return bool
     */
    protected function _checkIsAllowed()
    {
        $session = $this->request->getSession();
        $hasExistingAdmin = $session->read(self::CONFIG_KEY . '.hasExistingAdmin');
        if (PASSBOLT_IS_CONFIGURED && (empty($hasExistingAdmin) || $hasExistingAdmin === false)) {
            return true;
        }
        throw new ForbiddenException(__('You cannot access this section'));
    }
}
