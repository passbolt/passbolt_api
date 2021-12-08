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
use App\Model\Entity\Role;
use App\Service\Users\UserRegisterServiceInterface;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersRegisterController extends AppController
{
    public const USERS_REGISTER_EVENT_NAME = 'UsersRegisterController.register.success';

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        if (Configure::read('passbolt.registration.public') === true) {
            $this->Authentication->allowUnauthenticated(['registerGet', 'registerPost']);
        } else {
            $msg = __('Registration is not opened to public. Please contact your administrator.');
            throw new NotFoundException($msg);
        }

        return parent::beforeFilter($event);
    }

    /**
     * Register user action GET
     * Display a registration form
     *
     * @throws \Cake\Http\Exception\ForbiddenException if the current user is logged in
     * @param \App\Service\Users\UserRegisterServiceInterface $userRegisterService Service
     * @return void
     */
    public function registerGet(UserRegisterServiceInterface $userRegisterService)
    {
        // Do not allow logged in user to register
        if ($this->User->role() !== Role::GUEST) {
            throw new ForbiddenException(__('Only guest are allowed to register.'));
        }

        $this->set('title', Configure::read('passbolt.meta.description'));
        $userRegisterService->setTemplate($this->viewBuilder());
    }

    /**
     * Register user action POST
     *
     * @throws \Cake\Http\Exception\ForbiddenException if the current user is logged in
     * @param \App\Service\Users\UserRegisterServiceInterface $userRegisterService Service
     * @return void
     */
    public function registerPost(UserRegisterServiceInterface $userRegisterService)
    {
        if (!$this->request->is('json')) {
            throw new BadRequestException(__('This is not a valid Ajax/Json request.'));
        }

        // Do not allow logged in user to register
        if ($this->User->role() !== Role::GUEST) {
            throw new ForbiddenException(__('Only guest are allowed to register.'));
        }

        $user = $userRegisterService->register();

        $this->success(__('The operation was successful.'), $user);
    }
}
