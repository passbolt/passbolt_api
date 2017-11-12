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
use Cake\Core\Configure;
use Cake\Event\Event;
use App\Error\Exception\ValidationRuleException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotFoundException;
use JsonSchema\Exception\ValidationException;

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
     * @return void
     */
    public function registerPost()
    {
        // Do not allow logged in user to register
        if ($this->User->role() !== Role::GUEST) {
            throw new ForbiddenException(__('Only guest are allowed to register.'));
        }

        // By default users see the register form again
        // if something goes wrong they can try again
        $this->viewBuilder()
            ->setTemplatePath('/Users')
            ->setLayout('login')
            ->setTemplate('register');

        $user = $this->_registerUser();
        if ($user !== false) {
            // Display thank you page or user
            $this->viewBuilder()->setTemplate('register_thank_you');
            $this->success('The operation was successful.', $user);
        }
    }

    /**
     * @return mixed user or false if could not register the user
     */
    protected function _registerUser()
    {
        $user = $this->_buildAndValidateUser();
        if ($this->_assertValidationError($user)) {
            return false;
        }

        // Save user and create authentication token in one transaction
        // rollback if an exception is thrown
        $token = null;
        $this->Users->getConnection()->transactional(function () use ($user, &$token) {
            $this->_saveUser($user);
            $token = $this->AuthenticationTokens->generate($user->id);
        });
        if ($this->_assertValidationError($user)) {
            return false;
        }

        // Create an event to build email with token
        $event = new Event('UsersRegisterController.registerPost.success', $this, [
            'user' => $user, 'token' => $token
        ]);
        $this->getEventManager()->dispatch($event);

        return $user;
    }

    /**
     * Manage validation errors
     * @param \Cake\Datasource\EntityInterface $user user
     * @return bool
     */
    protected function _assertValidationError($user)
    {
        // If validation fails and request is json return the validation errors
        // Otherwise render the registration form with the errors
        if ($user->getErrors()) {
            if ($this->request->is('json')) {
                throw new ValidationRuleException(
                    __('Could not validate user data.'),
                    $user->getErrors(),
                    $this->Users
                );
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
        $user = $this->Users->buildEntity($this->request->getData(), $this->User->role());
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
