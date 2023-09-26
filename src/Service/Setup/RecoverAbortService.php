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
 * @since         3.6.0
 */

namespace App\Service\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Service\AuthenticationTokens\AuthenticationTokenGetService;
use App\Service\Users\UserGetService;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Class RecoverAbortService
 *
 * @package App\Service\Setup
 */
class RecoverAbortService
{
    use LocatorAwareTrait;
    use EventDispatcherTrait;

    public const RECOVER_ABORT_EVENT_NAME = 'RecoverAbortService.abort';

    /**
     * Recovery abort
     *
     * @throws \Cake\Http\Exception\BadRequestException if the user id is not a valid uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user was deleted, is already active or does not exist
     * @throws \Cake\Http\Exception\BadRequestException if no authentication token was provided
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is not a uuid
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is expired or invalid
     * @throws \Cake\Http\Exception\InternalErrorException if something went wrong when updating the data
     * @param string $userId uuid of the user
     * @param string $token authentication token value
     * @return void
     */
    public function abort(string $userId, string $token): void
    {
        $user = $this->getAndAssertUser($userId);
        $this->assertAndConsumeToken($token, $userId);

        $this->dispatchEvent(self::RECOVER_ABORT_EVENT_NAME, [
            'user' => $user,
        ]);
    }

    /**
     * Return the token or failÎ
     *
     * @param string $token token.token
     * @param string $userId User ID
     * @return void
     */
    protected function assertAndConsumeToken(string $token, string $userId): void
    {
        try {
            $tokenEntity = (new AuthenticationTokenGetService())
                ->getActiveNotExpiredOrFail($token, $userId, AuthenticationToken::TYPE_RECOVER);
        } catch (NotFoundException $exception) {
            throw new BadRequestException(__('The authentication token is not valid.'));
        }

        /** @var \App\Model\Table\AuthenticationTokensTable $authenticationTokensTable */
        $authenticationTokensTable = $this->fetchTable('AuthenticationTokens');
        $tokenEntity->active = false;
        if (!$authenticationTokensTable->save($tokenEntity)) {
            throw new InternalErrorException(__('The authentication token could not be saved.'));
        }
    }

    /**
     * Return the user for matching the requesting id
     *
     * @param string $userId the user uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user id is not a valid uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user was deleted or has not completed the setup
     * @return \App\Model\Entity\User
     */
    protected function getAndAssertUser(string $userId): User
    {
        try {
            return (new UserGetService())->getActiveNotDeletedNotDisabledOrFail($userId);
        } catch (NotFoundException $exception) {
            $msg = __('The user does not exist, has not completed the setup or was deleted.');
            throw new BadRequestException($msg);
        }
    }
}
