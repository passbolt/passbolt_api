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
 * @since         3.3.0
 */

namespace Passbolt\JwtAuthentication\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenAbstractService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenFetchUserService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenRenewalService;

class RefreshTokenController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated([
            'refreshPost',
        ]);

        return parent::beforeFilter($event);
    }

    /**
     * Serve a refresh token and a new JWT token.
     *
     * @throws \Cake\Http\Exception\BadRequestException if the refresh token is not set in the request.
     * @return void
     */
    public function refreshPost()
    {
        try {
            if ($this->isPayloadProvided()) {
                $accessToken = $this->handleWithPayload();
            } else {
                $accessToken = $this->handleWithCookie();
            }
        } catch (\Throwable $e) {
            throw new BadRequestException($e->getMessage());
        }

        $this->success(null, ['access_token' => $accessToken]);
    }

    /**
     * If the refresh token and the user ID is passed in the payload,
     * the authentication may be by-passed
     *
     * @return bool
     */
    protected function isPayloadProvided(): bool
    {
        return is_string($this->getRequest()->getData(RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY)) &&
            is_string($this->getRequest()->getData('user_id'));
    }

    /**
     * Renew the refresh token, set the refresh token in the response
     * as cookie
     * Return the new access token.
     *
     * @return string
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException When there is no user associated to this token.
     */
    protected function handleWithCookie(): string
    {
        $token = $this->getRequest()->getCookie(RefreshTokenAbstractService::REFRESH_TOKEN_COOKIE);
        $userId = (new RefreshTokenFetchUserService($token))->getUserIdFromToken();
        $this->renewRefreshTokenAndSetInResponseAsSecureCookie($token, $userId);

        return (new JwtTokenCreateService())->createToken($userId);
    }

    /**
     * Get the refresh token in the payload.
     * Return the new access token.
     *
     * @return string
     */
    protected function handleWithPayload(): string
    {
        $token = $this->getRequest()->getData(RefreshTokenAbstractService::REFRESH_TOKEN_DATA_KEY);
        $userId = $this->getRequest()->getData('user_id');
        $this->renewRefreshTokenAndSetInResponseAsSecureCookie($token, $userId);

        return (new JwtTokenCreateService())->createToken($userId);
    }

    /**
     * Consume the refresh token provided in the request, consume it and generate a new one.
     * Set that new refresh token in a secure http only cookie.
     *
     * @param string|null $oldRefreshToken Refresh token passed in the request
     * @param string|null $userId User Id
     * @return void
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException if the token is not found
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ConsumedRefreshTokenAccessException if the token was already consumed
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ExpiredRefreshTokenAccessException if the token is expired
     */
    protected function renewRefreshTokenAndSetInResponseAsSecureCookie(?string $oldRefreshToken, ?string $userId): void
    {
        $refreshService = new RefreshTokenRenewalService($userId, $oldRefreshToken);
        $refreshedToken = $refreshService->renewToken();
        $refreshHttpOnlySecureCookie = $refreshService->createHttpOnlySecureCookie($refreshedToken);
        $this->setResponse($this->getResponse()->withCookie($refreshHttpOnlySecureCookie));
    }
}
