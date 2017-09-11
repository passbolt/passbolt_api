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
     * @return void
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
     *
     * @return void
     */
    public function registerPost()
    {
        $user = $this->_buildAndValidateUser();
        if ($this->_handleValidationError($user)) {
            return;
        }

        // Save user and create authentication token in one transaction
        // rollback if an exception is thrown
        $this->Users->getConnection()->transactional(function () use ($user) {
            $this->_saveUser($user);
            $token = $this->_buildAuthToken($user);
        });

        if ($this->_handleValidationError($user)) {
            return;
        }

        // todo even to build email with token
        $this->success($user);
    }

    /**
     * Manage validation errors
     * @param \Cake\Datasource\EntityInterface $user user
     * @return bool
     */
    protected function _handleValidationError($user)
    {
        // If validation fails and request is json return the validation errors
        // Otherwise render the registration form with the errors
        if ($user->getErrors()) {
            if ($this->request->is('json')) {
                $this->set('errors', $user->errors());
                throw new ValidationException(__('Could not validate user data'));
            } else {
                $this->viewBuilder()
                    ->setTemplatePath('/Users')
                    ->setTemplate('register');
            }

            return true;
        }

        return false;
    }

    /**
     * Build and validate user entity from user input
     *
     * @return \Cake\Datasource\EntityInterface $user user entity
     */
    protected function _buildAndValidateUser()
    {
        // Build entity and perform basic check
        $user = $this->Users->newEntity(
            $this->request->getData(),
            ['validate' => 'register', 'associated' => ['Profiles']]
        );
        $this->set('user', $user);

        // No need to check rules if basic validation fails
        if ($user->getErrors()) {
            return $user;
        }
        $this->Users->checkRules($user);

        return $user;
    }

    /**
     * Build and save authentication token
     *
     * @param \Cake\Datasource\EntityInterface $user user entity
     * @throws InternalErrorException if token can not be saved
     * @return \App\Model\Entity\AuthenticationToken $token token
     */
    protected function _buildAuthToken($user)
    {
        $token = $this->AuthenticationTokens->newEntity(
            ['user_id' => $user->id],
            ['validate' => 'register']
        );
        if (!$this->AuthenticationTokens->save($token)) {
            throw new InternalErrorException(__('The authentication token could not be saved'));
        }
        $this->set('token', $token);

        return $token;
    }

    /**
     * Save a user entity
     *
     * @throws InternalErrorException if user could not be saved
     * @param \Cake\Datasource\EntityInterface $user user entity
     * @return void
     */
    protected function _saveUser($user)
    {
        $result = $this->Users->save($user, ['checkRules' => false, 'atomic' => false]);
        $this->set('user', $user);
        if (!$result) {
            throw new InternalErrorException(__('The user could not be saved'));
        }
    }

    /**
     * Handle success response
     *
     * @param array $body data for the body section
     * @return void
     */
    protected function success($body = null)
    {
        if (!$this->request->is('json')) {
            $this->viewBuilder()
                ->setTemplatePath('/Users')
                ->setTemplate('register_thank_you');
        } else {
            parent::success($body);
        }
    }
}
