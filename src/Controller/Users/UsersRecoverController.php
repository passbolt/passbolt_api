<?php
declare(strict_types=1);

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
use App\Model\Table\UsersTable;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 */
class UsersRecoverController extends AppController
{
    public const RECOVER_SUCCESS_EVENT_NAME = 'UsersRecoverController.recoverPost.success';

    /**
     * Before filter
     *
     * @param \Cake\Event\Event $event An Event instance
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

        $this->set('title', Configure::read('passbolt.meta.description'));
        $this->viewBuilder()
            ->setTemplatePath('/Auth')
            ->setLayout('default')
            ->setTemplate('triage');
        $this->success();
    }

    /**
     * Register user action POST
     *
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the username is not valid
     * @throws \Cake\Http\Exception\BadRequestException if the username is not provided
     */
    public function recoverPost()
    {
        if (!$this->request->is('json')) {
            throw new BadRequestException(__('This is not a valid Ajax/Json request.'));
        }

        // Do not allow logged in user to recover
        if (!in_array($this->User->role(), [Role::GUEST, Role::ADMIN])) {
            throw new ForbiddenException(__('Only guest are allowed to recover an account. Please logout first.'));
        }

        $this->_assertValidation();
        $user = $this->_assertRules();
        $token = null;
        $adminId = null;
        if ($user->active) {
            $token = $this->AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_RECOVER);
            $event = static::RECOVER_SUCCESS_EVENT_NAME;
        } else {
            // The user has not completed the setup, restart setup
            // Fixes https://github.com/passbolt/passbolt_api/issues/73
            $token = $this->AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_REGISTER);
            $event = UsersTable::AFTER_REGISTER_SUCCESS_EVENT_NAME;
            if ($this->User->role() === Role::ADMIN) {
                $adminId = $this->User->id();
            }
        }

        // Create an event to build email with token
        $options = ['user' => $user, 'token' => $token];
        if (isset($adminId)) {
            $options['adminId'] = $adminId;
        }
        $event = new Event($event, $this, $options);
        $this->getEventManager()->dispatch($event);

        $this->success(__('Recovery process started, check your email.'), $user);
    }

    /**
     * Assert some username data is provided
     *
     * @return \App\Model\Entity\User user entity
     * @throws \Cake\Http\Exception\BadRequestException if the username is not valid
     * @throws \Cake\Http\Exception\BadRequestException if the username is not provided
     */
    protected function _assertValidation()
    {
        $data = $this->request->getData();
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
     * @return mixed
     * @throws \Cake\Http\Exception\BadRequestException if the user does not exist or has been deleted
     */
    protected function _assertRules()
    {
        $data = $this->request->getData();
        $user = $this->Users->findRecover($data['username'])->first();

        if (empty($user)) {
            $msg = __('This user does not exist or has been deleted.') . ' ';
            if (Configure::read('passbolt.registration.public')) {
                $msg .= __('Please register and complete the setup first.');
            } else {
                $msg .= __('Please contact your administrator.');
            }
            throw new NotFoundException($msg);
        }

        return $user;
    }
}
