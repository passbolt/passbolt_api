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
use Cake\Event\Event;
use Cake\Network\Exception\InternalErrorException;
use JsonSchema\Exception\ValidationException;

class UserRegisterController extends AppController
{
    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow('registerGet');
        $this->Auth->allow('registerPost');

        $this->loadModel('Users');
        $this->loadModel('AuthenticationTokens');

        return parent::beforeFilter($event);
    }

    /**
     * Register user action GET
     * Display a registration form
     *
     * @return object \Cake\Http\Response or void
     */
    public function registerGet()
    {
        $this->viewBuilder()
            ->setTemplatePath('/Users')
            ->setTemplate('register');
        $user = $this->Users->newEntity();
        $this->set('user', $user);
    }

    /**
     * Register user action POST
     * @return object|void \Cake\Http\Response or void
     */
    public function registerPost() {
        // Validate user data
        $user = $this->Users->newEntity(
            $this->request->getData(),
            ['validate' => 'register', 'associated' => ['Profiles']]
        );

        // If validation fails and request is json return the validation errors
        // Otherwise render the registration form with the errors
        if ($user->errors()) {
            if ($this->request->is('json')) {
                $this->set('errors', $user->errors());
                throw new ValidationException(__('Could not validate user data'));
            }
            $this->viewBuilder()
                ->setTemplatePath('/Users')
                ->setTemplate('register');
            $this->set('user', $user);
            return;
        }

        // Save user and create authentication token
        // in one transaction
        $this->Users->getConnection()->transactional(function () use ($user) {
            if (!$this->Users->save($user)) {
                throw new InternalErrorException(__('The user could not be saved'));
            }
            $this->set('user', $user);
            $token = $this->AuthenticationTokens->newEntity(
                ['user_id' => $user->id], ['validate' => 'register']
            );
            if (!$this->AuthenticationTokens->save($token)) {
                throw new InternalErrorException(__('The authentication token could not be saved'));
            }
            $this->set('token', $token);
        });

        $this->viewBuilder()
            ->setTemplatePath('/Users')
            ->setTemplate('register_thank_you');
    }
}
