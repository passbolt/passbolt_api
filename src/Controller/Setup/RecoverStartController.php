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
use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 * @property \App\Model\Table\UsersTable $Users
 */
class RecoverStartController extends AppController
{
    use SetupControllerTrait;

    /**
     * Before filter
     *
     * @param \Cake\Event\Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['start']);
        $this->loadModel('AuthenticationTokens');
        $this->loadModel('Users');

        return parent::beforeFilter($event);
    }

    /**
     * Recover start
     *
     * @param string $userId uuid of the user
     * @param string $token uuid of the token
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the token is missing or not a uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user id is missing or not a uuid
     */
    public function start(string $userId, string $token): void
    {
        if ($this->request->is('json')) {
            $this->retrieveRecoverInfo($userId, $token);
        } else {
            $this->renderRecoverApplication();
        }
    }

    /**
     * Retrieve the recover info
     *
     * @param string $userId uuid of the user
     * @param string $token uuid of the token
     * @return void
     */
    private function retrieveRecoverInfo(string $userId, string $token): void
    {
        $this->_assertRequestSanity($userId, $token);
        $user = $this->findUser($userId);
        $token = $this->findToken($user, $token);
        $this->assertTokenExpiry($user, $token);
        $this->success(__('The operation was successful.'), ['user' => $user]);
    }

    /**
     * Find the user requesting the recover
     *
     * @param string $userId uuid of the user
     * @return \App\Model\Entity\User
     * @throw BadRequestException if the user cannot be found, is deleted or is inactive.
     */
    private function findUser(string $userId): User
    {
        $user = $this->Users->findSetupRecover($userId);
        if (empty($user)) {
            throw new BadRequestException(__('The user does not exist or is not active.'));
        }

        return $user;
    }

    /**
     * Find the recover token
     *
     * @param \App\Model\Entity\User $user user attempting to recover
     * @param string $token uuid of the token
     * @return \App\Model\Entity\AuthenticationToken
     * @throw BadRequestException if the token is not valid
     */
    private function findToken(User $user, string $token): AuthenticationToken
    {
        $finderOptions = ['userId' => $user->id, 'token' => $token];
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = $this->AuthenticationTokens->find('activeUserRecoveryToken', $finderOptions)->first();
        if (empty($token)) {
            throw new BadRequestException(__('The authentication token is not valid'));
        }

        return $token;
    }

    /**
     * Assert the token expiry. If the token is expired, regenerate a new one and throw an notify the client with an error.
     *
     * @param \App\Model\Entity\User $user user attempting to recover
     * @param \App\Model\Entity\AuthenticationToken $token the recovery token
     * @return void
     * @throw CustomValidationException if the token is expired
     */
    private function assertTokenExpiry(User $user, AuthenticationToken $token): void
    {
        $isExpired = $this->AuthenticationTokens->isExpired($token);
        if ($isExpired) {
            $error = [
                'token' => [
                    'expired' => 'The token is expired.',
                ],
            ];
            throw new CustomValidationException(__('The token is expired.'), $error);
        }
    }

    /**
     * Render the recover application
     *
     * @return void
     */
    private function renderRecoverApplication(): void
    {
        $this->set('title', Configure::read('passbolt.meta.description'));
        $this->viewBuilder()
            ->setTemplatePath('/Setup')
            ->setLayout('default')
            ->setTemplate('recoverStart');
    }
}
