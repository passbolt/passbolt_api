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
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotFoundException;

class UsersRegisterController extends AppController
{
    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        if (Configure::read('passbolt.registration.public') === true) {
            $this->Auth->allow('registerGet');
            $this->Auth->allow('registerPost');
        } else {
            throw new NotFoundException();
        };

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
            ->setLayout('login')
            ->setTemplate('register');

        $user = $this->Users->newEntity();
        $this->set('user', $user);
        $this->success();
    }

    /**
     * Register user action POST
     *
     * @return void
     */
    public function registerPost()
    {
        // By default users see the register form again
        // if something goes wrong they can try again
        $this->viewBuilder()
            ->setTemplatePath('/Users')
            ->setLayout('login')
            ->setTemplate('register');

        $user = $this->_buildAndValidateUser();
        if ($this->_handleValidationError($user)) {
            return;
        }

        // Save user and create authentication token in one transaction
        // rollback if an exception is thrown
        $token = null;
        $this->Users->getConnection()->transactional(function () use ($user, &$token) {
            $this->_saveUser($user);
            $token = $this->AuthenticationTokens->generate($user->id);
        });
        if ($this->_handleValidationError($user)) {
            return;
        }

        // Create an event to build email with token
        $event = new Event('UsersRegisterController.registerPost.success', $this, [
            'user' => $user, 'token' => $token
        ]);
        $this->getEventManager()->dispatch($event);

        // Display thank you page or user
        $this->viewBuilder()->setTemplate('register_thank_you');
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
                $this->set('errors', $user->getErrors());
                throw new BadRequestException(__('Could not validate user data.'));
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
            [
                'validate' => 'register',
                'accessibleFields' => [
                    'username' => true,
                    'profile' => true,
                    'active' => true, // reset in beforeMarshal
                    'deleted' => true, // idem
                    'role_id' => true, // idem
                ],
                'associated' => [
                    'Profiles' => [
                        'validate' => 'register',
                        'accessibleFields' => [
                            'first_name' => true,
                            'last_name' => true
                        ]
                    ]
                ]
            ]
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
            throw new InternalErrorException(__('The user could not be saved.'));
        }
    }
}
