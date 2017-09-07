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
        $this->Auth->allow('registerThankyou');

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
        $this->loadModel('Users');
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
        $this->loadModel('Users');
        $user = $this->Users->newEntity(
            $this->request->getData(),
            ['validate' => 'register']
        );
        // Entity failed validation.
        if ($user->errors()) {
            $this->viewBuilder()
                ->setTemplatePath('/Users')
                ->setTemplate('register');
            $this->set('user', $user);
        }
        if ($this->Users->save($user)) {
            echo 'saved'; die;
        }
    }
}
