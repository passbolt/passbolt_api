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
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Role;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;

class UsersRecoverController extends AppController
{
    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow('recoverGet');
        $this->Auth->allow('recoverPost');
        $this->loadModel('Users');
        $this->loadModel('AuthenticationTokens');

        return parent::beforeFilter($event);
    }

    /**
     * Recover user action GET
     * Display a recovery form
     *
     * @return void
     */
    public function recoverGet()
    {
        // Do not allow logged in user to recover
        if ($this->User->role() !== Role::GUEST) {
            $this->Auth->logout();
        }

        $this->viewBuilder()
            ->setTemplatePath('/Users')
            ->setLayout('login')
            ->setTemplate('recover');

        $user = $this->Users->newEntity();
        $this->set('user', $user);
        $this->success();
    }

    /**
     * Register user action POST
     *
     * @throws BadRequestException if the username is not provided
     * @throws BadRequestException if the username is not valid
     * @return void
     */
    public function recoverPost()
    {
        // Do not allow logged in user to recover
        if ($this->User->role() !== Role::GUEST) {
            throw new ForbiddenException(__('Only guest are allowed to recover an account. Please logout first.'));
        }

        $user = $this->Users->newEntity();
        try {
            $this->_assertValidation();
            $user = $this->_assertRules();
            $token = null;

            if ($user->active) {
                $token = $this->AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_RECOVER);
                $event = 'UsersRecoverController.recoverPost.success';
            } else {
                // The user has not completed the setup, restart setup
                // Fixes https://github.com/passbolt/passbolt_api/issues/73
                $token = $this->AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_REGISTER);
                $event = 'UsersRecoverController.registerPost.success';
            }

            // Create an event to build email with token
            $event = new Event($event, $this, ['user' => $user, 'token' => $token]);
            $this->getEventManager()->dispatch($event);
        } catch (BadRequestException $e) {
            if ($this->request->is('json')) {
                // If JSON is expected let the ErrorController handle it
                throw $e;
            } else {
                // If it is not a JSON request the users see the recover form again
                // with some error message so that they can give it another shot
                $user->setError('username', $e->getMessage(), true);
                $this->set('user', $user);
                $this->viewBuilder()
                    ->setTemplatePath('/Users')
                    ->setLayout('login')
                    ->setTemplate('recover');

                return;
            }
        }

        if ($this->request->is('json')) {
            $this->success(__('Recovery process started, check your email.'), $user);
        } else {
            $this->viewBuilder()
                ->setTemplatePath('/Users')
                ->setLayout('login')
                ->setTemplate('recover_thank_you');
        }
    }

    /**
     * Assert some username data is provided
     *
     * @throws BadRequestException if the username is not provided
     * @throws BadRequestException if the username is not valid
     * @return \Cake\Datasource\EntityInterface user entity
     */
    protected function _assertValidation()
    {
        $data = $this->_formatRequestData();
        if (!isset($data['username']) || empty($data['username'])) {
            throw new BadRequestException(__('Please provide a valid email address.'));
        }
        $user = $this->Users->newEntity(
            $data,
            ['validate' => 'recover', 'accessibleFields' => ['username' => true]]
        );
        if ($user->getErrors()) {
            throw new BadRequestException(__('Please provide a valid email address.'));
        }

        return $user;
    }

    /**
     * Assert the user can actually perform a recovery on their account
     *
     * @throws BadRequestException if the user does not exist or has been deleted
     * @return mixed
     */
    protected function _assertRules()
    {
        $data = $this->_formatRequestData();
        $user = $this->Users->findRecover($data['username'])->first();

        if (empty($user)) {
            $msg = __('This user does not exist or has been deleted.') . ' ';
            if (Configure::read('passbolt.registration.public')) {
                $msg .= __('Please register and complete the setup first.');
            } else {
                $msg .= __('Please contact your administrator.');
            }
            throw new BadRequestException($msg);
        }

        return $user;
    }

    /**
     * Format request data formatted for API v1 to API v2 format
     * Example:
     * - API v1: ['User' => ['username' => 'ada@passbolt.com']]
     * - API v2: ['username' => 'ada@passbolt.com']
     *
     * @return null|array $data
     */
    protected function _formatRequestData()
    {
        $data = $this->request->getData();

        if (isset($data['User'])) {
            return $data['User'];
        }

        return $data;
    }
}
