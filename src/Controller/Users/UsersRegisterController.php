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
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\UsersTable Users
 */
class UsersRegisterController extends AppController
{
    /**
     * Before filter
     *
     * @param \Cake\Event\Event $event An Event instance
     * @throws \Cake\Http\Exception\NotFoundException if registration is not set to public
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
        }

        $this->loadModel('Users');

        return parent::beforeFilter($event);
    }

    /**
     * Register user action GET
     * Display a registration form
     *
     * @throws \Cake\Http\Exception\ForbiddenException if the current user is logged in
     * @return void
     */
    public function registerGet()
    {
        // Do not allow logged in user to register
        if ($this->User->role() !== Role::GUEST) {
            throw new ForbiddenException(__('Only guest are allowed to register.'));
        }

        $this->set('title', Configure::read('passbolt.meta.description'));
        $this->viewBuilder()
            ->setTemplatePath('/Auth')
            ->setLayout('default')
            ->setTemplate('triage');
    }

    /**
     * Register user action POST
     *
     * @throws \Cake\Http\Exception\ForbiddenException if the current user is logged in
     * @return void
     */
    public function registerPost()
    {
        if (!$this->request->is('json')) {
            throw new BadRequestException(__('This is not a valid Ajax/Json request.'));
        }

        // Do not allow logged in user to register
        if ($this->User->role() !== Role::GUEST) {
            throw new ForbiddenException(__('Only guest are allowed to register.'));
        }

        $data = $this->request->getData();
        $user = $this->Users->register($data);
        $this->success(__('The operation was successful.'), $user);
    }
}
