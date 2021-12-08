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

use App\Controller\Users\UsersRecoverController;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\Event;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\ServerRequest;
use Cake\View\ViewBuilder;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 * @property \App\Model\Table\UsersTable $Users
 */
class UserRecoverService implements UserRecoverServiceInterface
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
        $this->loadModel('AuthenticationTokens');
        $this->loadModel('Users');
    }

    /**
     * @inheritDoc
     */
    public function recover(UserAccessControl $uac): void
    {
        $this->assertValidation();
        $user = $this->assertRules();
        $token = null;
        $adminId = null;
        if ($user->active) {
            $token = $this->AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_RECOVER);
            $event = UsersRecoverController::RECOVER_SUCCESS_EVENT_NAME;
        } else {
            // The user has not completed the setup, restart setup
            // Fixes https://github.com/passbolt/passbolt_api/issues/73
            $token = $this->AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_REGISTER);
            $event = UsersTable::AFTER_REGISTER_SUCCESS_EVENT_NAME;
            if ($uac->isAdmin()) {
                $adminId = $uac->getId();
            }
        }

        // Create an event to build email with token
        $options = ['user' => $user, 'token' => $token];
        if (isset($adminId)) {
            $options['adminId'] = $adminId;
        }
        $event = new Event($event, $this, $options);
        $this->getEventManager()->dispatch($event);
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

    /**
     * Assert some username data is provided
     *
     * @return \App\Model\Entity\User user entity
     * @throws \Cake\Http\Exception\BadRequestException if the username is not valid
     * @throws \Cake\Http\Exception\BadRequestException if the username is not provided
     */
    protected function assertValidation(): User
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
     * @return \App\Model\Entity\User
     * @throws \Cake\Http\Exception\BadRequestException if the user does not exist or has been deleted
     */
    protected function assertRules(): User
    {
        $data = $this->request->getData();
        /** @var \App\Model\Entity\User|null $user */
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
