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
use App\Service\Users\UserRecoverServiceInterface;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 */
class UsersRecoverController extends AppController
{
    public const RECOVER_SUCCESS_EVENT_NAME = 'UsersRecoverController.recoverPost.success';

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['recoverGet', 'recoverPost']);

        return parent::beforeFilter($event);
    }

    /**
     * Recover user action GET
     * Display a recovery form
     *
     * @param \App\Service\Users\UserRecoverServiceInterface $userRecoverService User recover service
     * @return void
     */
    public function recoverGet(UserRecoverServiceInterface $userRecoverService)
    {
        // Do not allow logged in user to recover
        if ($this->User->role() !== Role::GUEST) {
            $this->Authentication->logout();
        }

        $this->set('title', Configure::read('passbolt.meta.description'));
        $userRecoverService->setTemplate($this->viewBuilder());
        $this->success();
    }

    /**
     * Register user action POST
     *
     * @param \App\Service\Users\UserRecoverServiceInterface $userRecoverService User recover service
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the username is not valid
     * @throws \Cake\Http\Exception\BadRequestException if the username is not provided
     */
    public function recoverPost(UserRecoverServiceInterface $userRecoverService)
    {
        if (!$this->request->is('json')) {
            throw new BadRequestException(__('This is not a valid Ajax/Json request.'));
        }

        // Do not allow logged in user to recover
        if (!in_array($this->User->role(), [Role::GUEST, Role::ADMIN])) {
            throw new ForbiddenException(__('Only guest are allowed to recover an account. Please logout first.'));
        }

        $userRecoverService->recover($this->User->getAccessControl());
        $this->success(__('Recovery process started, check your email.'));
    }
}
