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
 * @since         3.5.0
 */

namespace App\Service\Users;

use App\Controller\Users\UsersRegisterController;
use App\Model\Entity\User;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\ServerRequest;
use Cake\View\ViewBuilder;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class UserRegisterService implements UserRegisterServiceInterface
{
    use EventDispatcherTrait;
    use ModelAwareTrait;

    /**
     * @var \Cake\Http\ServerRequest
     */
    public $request;

    /**
     * @param \Cake\Http\ServerRequest $serverRequest Server request
     */
    public function __construct(ServerRequest $serverRequest)
    {
        $this->request = $serverRequest;
        $this->loadModel('Users');
    }

    /**
     * @inheritDoc
     */
    public function register(): User
    {
        $data = $this->request->getData();
        $user = $this->Users->register($data);

        $this->dispatchEvent(UsersRegisterController::USERS_REGISTER_EVENT_NAME, [
            'user' => $user,
            'data' => $this->request->getData(),
        ]);

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function setTemplate(ViewBuilder $viewBuilder): void
    {
        $viewBuilder
            ->setTemplatePath('Auth')
            ->setLayout('default')
            ->setTemplate('triage');
    }
}
