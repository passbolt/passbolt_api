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
namespace App\Controller\Auth;

use App\Controller\AppController;
use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use App\Utility\UserAction;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;

class AuthLoginController extends AppController
{
    /**
     * Before filter
     *
     * @param \Cake\Event\Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow([
            'loginGet',
            'loginPost',
        ]);

        return parent::beforeFilter($event);
    }

    /**
     * User login get action
     * Display the login page
     *
     * @return void
     */
    public function loginGet()
    {
        // Do not allow logged in user to login again
        if ($this->User->role() !== Role::GUEST) {
            $this->redirect('/'); // user is already logged in
        }

        $this->set('title', Configure::read('passbolt.meta.description'));

        $this->viewBuilder()
            ->setLayout('default')
            ->setTemplatePath('/Auth')
            ->setTemplate('triage');
    }

    /**
     * User login post action
     *
     * @return void
     */
    public function loginPost()
    {
        if (!$this->request->is('json')) {
            throw new BadRequestException(__('This is not a valid Ajax/Json request.'));
        }

        $user = $this->Auth->identify();
        $gpgAuth = $this->Auth->getAuthenticate('Gpg');
        $this->response = $gpgAuth->getUpdatedResponse();

        if ($user) {
            $this->Auth->setUser($user);
            UserAction::getInstance()->setUserAccessControl(new UserAccessControl(
                $user['role']['name'],
                $user['id']
            ));
            $this->success(__('You are successfully logged in.'), $user);
        } else {
            $message = 'The authentication failed.';
            $debug = $this->response->getHeader('X-GPGAuth-Debug');
            if (isset($debug) && count($debug) === 1) {
                $message .= ' ' . $debug[0];
            }
            $this->error($message);
        }
    }
}
