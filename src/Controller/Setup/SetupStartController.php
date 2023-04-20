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
namespace App\Controller\Setup;

use App\Controller\AppController;
use App\Model\Entity\Role;
use App\Service\Setup\SetupStartServiceInterface;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 * @property \App\Model\Table\UsersTable $Users
 */
class SetupStartController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['start']);

        return parent::beforeFilter($event);
    }

    /**
     * Setup start
     *
     * @param \App\Service\Setup\SetupStartServiceInterface $infoService info service
     * @param string $userId uuid of the user
     * @param string $token uuid of the token
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the token is missing or not a uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user id is missing or not a uuid
     */
    public function start(SetupStartServiceInterface $infoService, string $userId, string $token): void
    {
        if ($this->request->is('json')) {
            // Do not allow logged in user to start setup
            if ($this->User->role() !== Role::GUEST) {
                throw new ForbiddenException(__('Only guests are allowed to start setup.'));
            }
            $data = $infoService->getInfo($userId, $token);
            $this->success(__('The operation was successful.'), $data);
        } else {
            $this->set('title', Configure::read('passbolt.meta.description'));
            $infoService->setTemplate($this->viewBuilder());
        }
    }
}
