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
use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use Aura\Intl\Exception;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;

class UsersRegisterController extends AppController
{
    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @throws NotFoundException if registration is not set to public
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        if (Configure::read('passbolt.registration.public') === true) {
            $this->Auth->allow('registerGet');
            $this->Auth->allow('registerPost');
        } else {
            $msg = __('Registration is not opened to public. Please contact your administrator.');
            throw new NotFoundException($msg);
        };

        $this->loadModel('Users');

        return parent::beforeFilter($event);
    }

    /**
     * Register user action GET
     * Display a registration form
     *
     * @throws ForbiddenException if the current user is logged in
     * @return void
     */
    public function registerGet()
    {
        // Do not allow logged in user to register
        if ($this->User->role() !== Role::GUEST) {
            throw new ForbiddenException(__('Only guest are allowed to register.'));
        }
        $this->viewBuilder()
            ->setTemplatePath('/Users')
            ->setLayout('login')
            ->setTemplate('register');

        $user = $this->Users->newEntity();
        $this->set('user', $user);
        $this->success();
    }

    /**
     * Register user action POST
     *
     * @throws ForbiddenException if the current user is logged in
     * @return void
     */
    public function registerPost()
    {
        // Do not allow logged in user to register
        if ($this->User->role() !== Role::GUEST) {
            throw new ForbiddenException(__('Only guest are allowed to register.'));
        }

        $data = $this->_formatRequestData();
        try {
            $user = $this->Users->register($data);
            $this->viewBuilder()
                ->setTemplatePath('/Users')
                ->setLayout('login')
                ->setTemplate('register_thank_you');
            $this->success(__('The operation was successful.'), $user);
        } catch (ValidationException $exception) {
            if ($this->request->is('json')) {
                throw $exception;
            }
            // By default users see the register form again
            // if something goes wrong they can try again
            $this->set('user', $exception->getEntity());
            $this->viewBuilder()
                ->setTemplatePath('/Users')
                ->setLayout('login')
                ->setTemplate('register');
        } catch (InternalErrorException $exception) {
            throw $exception;
        }
    }

    /**
     * Format request data formatted for API v1 to API v2 format
     * Example:
     * - API v1: ['User' => ['username' => 'ada@passbolt.com'], 'Profile' => ['first_name' => 'ada' ...]]
     * - API v2: ['username' => 'ada@passbolt.com', 'profile' => ['first_name' => 'ada' ...]]
     *
     * @return null|array $data
     */
    protected function _formatRequestData()
    {
        $data = $this->request->getData();
        $result = null;
        if (isset($data['User'])) {
            if (!empty($data)) {
                foreach ($data['User'] as $property => $value) {
                    $result[$property] = $value;
                }
                foreach ($data['Profile'] as $property => $value) {
                    $result['profile'][$property] = $value;
                }
            }
        } else {
            $result = $data;
        }

        return $result;
    }
}
