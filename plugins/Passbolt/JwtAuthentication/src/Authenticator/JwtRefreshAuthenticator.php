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
 * @since         4.0.0
 */
namespace Passbolt\JwtAuthentication\Authenticator;

use App\Model\Entity\User;
use Authentication\Authenticator\AbstractAuthenticator;
use Authentication\Authenticator\Result;
use Authentication\Authenticator\ResultInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenUserIdMismatchException;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenAbstractService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenFetchUserService;
use Psr\Http\Message\ServerRequestInterface;

class JwtRefreshAuthenticator extends AbstractAuthenticator
{
    /**
     * @inheritDoc
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        /** @var \Cake\Http\ServerRequest $request */
        try {
            if ($this->isPayloadProvided($request)) {
                $user = $this->getUserViaPayload($request);
            } else {
                $user = $this->getUserViaCookie($request);
            }

            return new Result(compact('user'), Result::SUCCESS);
        } catch (\Exception $exception) {
            throw new BadRequestException($exception->getMessage());
        }
    }

    /**
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return \App\Model\Entity\User
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenUserIdMismatchException if the user_id and the token mismatch.
     */
    protected function getUserViaPayload(ServerRequest $request): User
    {
        $token = $request->getData(RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY);
        $userId = $request->getData('user_id');
        $tokenUserId = (new RefreshTokenFetchUserService($token))->getUserIdFromToken();

        if ($tokenUserId === $userId) {
            return $this->findUser($userId);
        } else {
            throw new RefreshTokenUserIdMismatchException(
                __('The user ID does not match the user refresh token.')
            );
        }
    }

    /**
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return \App\Model\Entity\User
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException When there is no user associated to this token.
     */
    protected function getUserViaCookie(ServerRequest $request): User
    {
        $token = $request->getCookie(RefreshTokenAbstractService::REFRESH_TOKEN_COOKIE);
        $userId = (new RefreshTokenFetchUserService($token))->getUserIdFromToken();

        return $this->findUser($userId);
    }

    /**
     * @param string $userId uuid
     * @return \App\Model\Entity\User
     * @throws \Cake\Http\Exception\NotFoundException if the user cannot be found, is deleted, is not active
     */
    public function findUser(string $userId): User
    {
        try {
            /** @var \App\Model\Table\UsersTable $Users */
            $Users = TableRegistry::getTableLocator()->get('Users');
            /** @var \App\Model\Entity\User $user */
            $user = $Users->find('activeNotDeletedContainRole')
                ->where(['Users.id' => $userId])
                ->firstOrFail();

            return $user;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            throw new NotFoundException(__('The user does not exist or has been deleted.'));
        }
    }

    /**
     * Is the user ID and the refresh token in the payload.
     *
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return bool
     */
    protected function isPayloadProvided(ServerRequest $request): bool
    {
        return is_string($request->getData(RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY)) &&
            is_string($request->getData('user_id'));
    }
}
