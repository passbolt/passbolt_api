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
use Cake\Validation\Validation;
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
    public function recover(UserAccessControl $uac): User
    {
        $user = $this->getUserOrFail();
        $options = ['user' => $user];
        $options['case'] = $this->assertRecoveryCase();

        if ($user->active) {
            $options['token'] = $this->AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_RECOVER);
            $eventName = UsersRecoverController::RECOVER_SUCCESS_EVENT_NAME;
        } else {
            // The user has not completed the setup, restart setup
            // Fixes https://github.com/passbolt/passbolt_api/issues/73
            $options['token'] = $this->AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_REGISTER);
            $eventName = UsersTable::AFTER_REGISTER_SUCCESS_EVENT_NAME;
            if ($uac->isAdmin()) {
                $options['adminId'] = $uac->getId();
            }
        }

        // Create an event to build email with token
        $event = new Event($eventName, $this, $options);
        $this->getEventManager()->dispatch($event);

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

    /**
     * @return string self::ACCOUNT_RECOVERY_CASE_DEFAULT default
     */
    protected function assertRecoveryCase(): string
    {
        $case = $this->request->getData('case') ?? null;

        if (!isset($case)) {
            return self::ACCOUNT_RECOVERY_CASE_DEFAULT;
        }
        if (!is_string($case)) {
            throw new BadRequestException(__('Account recovery case must be a string.'));
        }
        if (!in_array($case, self::ACCOUNT_RECOVERY_CASES)) {
            throw new BadRequestException(__('Account recovery reason not supported.'));
        }

        return $case;
    }

    /**
     * Assert some username data is provided
     *
     * @throws \Cake\Http\Exception\BadRequestException if the username is not valid
     * @throws \Cake\Http\Exception\BadRequestException if the username is not provided
     * @return string validated username
     */
    protected function assertUsername(): string
    {
        $username = $this->request->getData('username') ?? null;
        if (!isset($username) || !is_string($username) || !Validation::email($username)) {
            throw new BadRequestException(__('Please provide a valid email address.'));
        }

        $user = $this->Users->newEntity([
            'username' => $username,
        ], [
            'validate' => 'recover',
            'accessibleFields' => [
                'username' => true,
            ],
        ]);

        if ($user->getErrors()) {
            throw new BadRequestException(__('Please provide a valid email address.'));
        }

        return $username;
    }

    /**
     * Assert the user can actually perform a recovery on their account
     *
     * @return \App\Model\Entity\User
     * @throws \Cake\Http\Exception\BadRequestException if the user does not exist or has been deleted
     */
    protected function getUserOrFail(): User
    {
        $username = $this->assertUsername();

        /** @var \App\Model\Entity\User|null $user */
        $user = $this->Users->findByUsername($username)->first();

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
