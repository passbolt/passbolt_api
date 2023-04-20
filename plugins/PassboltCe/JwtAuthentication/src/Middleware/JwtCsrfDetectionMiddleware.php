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
namespace Passbolt\JwtAuthentication\Middleware;

use App\Middleware\CsrfProtectionMiddleware;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Passbolt\JwtAuthentication\Service\Middleware\JwtRequestDetectionService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JwtCsrfDetectionMiddleware implements MiddlewareInterface
{
    /**
     * Generally, Csrf detection is detected for the endpoints of the present plugin.
     * An exception applies for the refresh token endpoint when requested with a refresh token
     * set in a cookie.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     * @return \Psr\Http\Message\ResponseInterface The response.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        /** @var \Cake\Http\ServerRequest $request */
        $requestService = (new JwtRequestDetectionService($request));

        // If request pointing to the Web browser / browser extension refresh end point, CSRF should be active
        // Else if request pointing to any other routes of the JwtAuthentication Plugin, the Csrf Middleware shall be skipped
        // Else do nothing
        if ($this->isBrowserRefreshEndpoint($request, $requestService)) {
            $this->setCsrfProtectionActiveInConfig(true);
        } elseif ($requestService->useJwtAuthentication() === true) {
            $this->setCsrfProtectionActiveInConfig(false);
        }

        return $handler->handle($request);
    }

    /**
     * The refresh endpoint requested by browser should have Csrf protection.
     * Check the route, the method (no GET), and the presence of the token in Cookie.
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @param \Passbolt\JwtAuthentication\Service\Middleware\JwtRequestDetectionService $service Jwt Request Service
     * @return bool
     */
    protected function isBrowserRefreshEndpoint(ServerRequest $request, JwtRequestDetectionService $service): bool
    {
        if ($request->is('get')) {
            return false;
        }
        $isRefreshEndPoint = $request->getAttribute('params')['_matchedRoute'] === '/auth/jwt/refresh';
        $isBrowserSolution = $service->isJwtRefreshTokenSetInCookie();

        return $isRefreshEndPoint && $isBrowserSolution;
    }

    /**
     * See the
     *
     * @see \App\Middleware\CsrfProtectionMiddleware::skipCsrfProtection()
     * @param bool $isActive value to set
     * @return void
     */
    protected function setCsrfProtectionActiveInConfig(bool $isActive): void
    {
        Configure::write(CsrfProtectionMiddleware::PASSBOLT_SECURITY_CSRF_PROTECTION_ACTIVE_CONFIG, $isActive);
    }
}
