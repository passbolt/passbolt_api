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

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Utility\Hash;
use Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenAbstractService;
use Psr\Http\Message\ServerRequestInterface;

class JwtRequestDetectionService
{
    use FeaturePluginAwareTrait;

    public const IS_JWT_AUTH_REQUEST = 'is_jwt_auth_request';

    /**
     * @var \Cake\Http\ServerRequest
     */
    protected $request;

    /**
     * JwtRequestDetectionService constructor.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     */
    public function __construct(ServerRequestInterface $request)
    {
        /** @var \Cake\Http\ServerRequest $request */
        $this->request = $request;
    }

    /**
     * Passes an attribute to the request to inform other layers using the request
     * that the request is either:
     *  - with JWT Authentication
     *  - with a refresh token is found in the cookies
     *  - the route matches one of the JwtAuthentication plugin
     *
     * @return bool
     */
    public function useJwtAuthentication(): bool
    {
        // Already cached
        if ($this->request->getAttribute(self::IS_JWT_AUTH_REQUEST)) {
            return true;
        }

        if (!$this->isFeaturePluginEnabled('JwtAuthentication')) {
            return false;
        }

        // JWT cannot be used because of configuration issue
        if (!$this->isJwtServerKeyUsable()) {
            return false;
        }

        // Uses if it's a JWT login route
        if ($this->isJWTAuthRoute()) {
            return true;
        }

        // Uses if the access token is set in header or if a refresh_token is set in the cookies
        if ($this->isJwtAccessTokenSetInHeader() || $this->isJwtRefreshTokenSetInCookie()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isJwtServerKeyUsable(): bool
    {
        try {
            (new JwksGetService())->getPublicKey();
        } catch (InvalidJwtKeyPairException $e) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isJWTAuthRoute(): bool
    {
        $params = $this->request->getAttribute('params', null);
        if (isset($params) && !$this->request->is('get')) {
            $plugin = Hash::get($params, 'plugin');
            if ($plugin === 'Passbolt/JwtAuthentication') {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the access token is set in header.
     *
     * @return bool
     */
    public function isJwtAccessTokenSetInHeader(): bool
    {
        return !empty($this->request->getHeaderLine(JwtAuthenticationService::JWT_HEADER));
    }

    /**
     * Checks if a refresh token is set as cookies.
     *
     * @return bool
     */
    public function isJwtRefreshTokenSetInCookie(): bool
    {
        return !empty($this->request->getCookie(RefreshTokenAbstractService::REFRESH_TOKEN_COOKIE));
    }
}
