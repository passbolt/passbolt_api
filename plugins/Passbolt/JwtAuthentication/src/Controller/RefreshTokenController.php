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

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Controller\AppController;
use App\Model\Entity\AuthenticationToken;
use Cake\Http\Exception\BadRequestException;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenRenewalService;

class RefreshTokenController extends AppController
{
    /**
     * Serve a refresh token and a new JWT token.
     *
     * @param \App\Authenticator\SessionIdentificationServiceInterface $sessionIdentificationService Access token provided in the request and validated by the JwtRefreshAuthenticator
     * @throws \Cake\Http\Exception\BadRequestException if the refresh token is not set in the request.
     * @return void
     * @see JwtRefreshTokenAuthenticator
     */
    public function refreshPost(SessionIdentificationServiceInterface $sessionIdentificationService)
    {
        try {
            /** @var \App\Model\Entity\AuthenticationToken $refreshToken */
            $refreshToken = $sessionIdentificationService->getSessionIdentifier($this->getRequest());
            $accessToken = $this->renewRefreshTokenAndSetInResponseAsSecureCookie($refreshToken);
        } catch (\Exception $e) {
            throw new BadRequestException($e->getMessage());
        }

        $this->success(null, ['access_token' => $accessToken]);
    }

    /**
     * Consume the refresh token provided in the request, consume it and generate a new one.
     * Set that new refresh token in a secure http only cookie.
     *
     * @param \App\Model\Entity\AuthenticationToken $oldRefreshToken Refresh token passed in the request
     * @return string Access token newlyy created and associated to the new refresh token
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\RefreshTokenNotFoundException if the token is not found
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ConsumedRefreshTokenAccessException if the token was already consumed
     * @throws \Passbolt\JwtAuthentication\Error\Exception\RefreshToken\ExpiredRefreshTokenAccessException if the token is expired
     */
    protected function renewRefreshTokenAndSetInResponseAsSecureCookie(AuthenticationToken $oldRefreshToken): string
    {
        $accessToken = (new JwtTokenCreateService())->createToken($oldRefreshToken->user_id);
        $refreshService = (new RefreshTokenRenewalService());
        $refreshedToken = $refreshService->renewToken($this->getRequest(), $oldRefreshToken, $accessToken);
        $refreshHttpOnlySecureCookie = $refreshService->createHttpOnlySecureCookie($refreshedToken);
        $this->setResponse($this->getResponse()->withCookie($refreshHttpOnlySecureCookie));

        return $accessToken;
    }
}
