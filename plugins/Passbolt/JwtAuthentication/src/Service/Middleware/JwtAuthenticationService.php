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
namespace Passbolt\JwtAuthentication\Service\Middleware;

use Authentication\AuthenticationService;
use Authentication\Authenticator\ResultInterface;
use Cake\Http\ServerRequest;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use Psr\Http\Message\ServerRequestInterface;

class JwtAuthenticationService extends AuthenticationService
{
    public const JWT_HEADER = 'Authorization';

    /**
     * @inheritDoc
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->loadIdentifier('Authentication.JwtSubject', [
            'resolver' => [
                'className' => 'Authentication.Orm',
                'finder' => 'activeNotDeletedContainRole',
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function authenticate(ServerRequestInterface $request): ResultInterface
    {
        /** @var \Cake\Http\ServerRequest $request */
        if ($this->isLoginEndpointPost($request)) {
            $this->loadAuthenticator('Passbolt/JwtAuthentication.GpgJwt');
        } elseif ($this->isRefreshEndpointPost($request)) {
            $this->loadAuthenticator('Passbolt/JwtAuthentication.JwtRefreshToken');
        } else {
            $this->loadAuthenticator('Authentication.Jwt', [
                'header' => self::JWT_HEADER,
                'algorithm' => JwtTokenCreateService::JWT_ALG,
                'secretKey' => file_get_contents(JwksGetService::PUBLIC_KEY_PATH),
                'returnPayload' => false,
            ]);
        }

        return parent::authenticate($request);
    }

    /**
     * Is the user attempting to log in.
     * If so, the authentication with access token in header is ignored.
     *
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return bool
     */
    public function isLoginEndpointPost(ServerRequest $request): bool
    {
        $path = str_replace('.json', '', $request->getUri()->getPath());
        $isLoginPath = ($path === '/auth/jwt/login');

        return $isLoginPath && $request->is('POST');
    }

    /**
     * Is the user attempting to get a refresh token.
     * If so, the user is identified based on the refresh token provided.
     *
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return bool
     */
    public function isRefreshEndpointPost(ServerRequest $request): bool
    {
        $path = str_replace('.json', '', $request->getUri()->getPath());
        $isRefreshEndpoint = ($path === '/auth/jwt/refresh');

        return $isRefreshEndpoint && $request->is('POST');
    }
}
