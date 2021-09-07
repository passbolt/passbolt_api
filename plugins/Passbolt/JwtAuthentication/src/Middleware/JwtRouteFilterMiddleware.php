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

use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Passbolt\JwtAuthentication\Service\Middleware\JwtRequestDetectionService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JwtRouteFilterMiddleware implements MiddlewareInterface
{
    /**
     * Filters routes that should not be accessed in JWT context.
     *
     * E.g.: the session-based logout route should be blocked if a JWT access token is
     * placed in the header. In such case, the logout response would be returned as successful,
     * but the access token would not be deactivated, and thus logout not successful.
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
        $this->throwExceptionIfRouteIsNotAllowedWithJwtAuth($request);

        return $handler->handle($request);
    }

    /**
     * Returns the routes that cannot be accessed if a JWT access token os placed in the header
     *
     * @return string[]
     */
    protected function getBlockedRoutes(): array
    {
        return [
            '/auth/login',
            '/auth/logout',
        ];
    }

    /**
     * Checks that the request is JWT related, and throws an exception if the required routes are not allowed.
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException
     */
    protected function throwExceptionIfRouteIsNotAllowedWithJwtAuth(ServerRequest $request): void
    {
        if ($request->getAttribute(JwtRequestDetectionService::IS_JWT_AUTH_REQUEST)) {
            $route = $request->getAttribute('params')['_matchedRoute'] ?? null;
            if (in_array($route, $this->getBlockedRoutes())) {
                throw new BadRequestException(
                    __('The route {0} is not permitted with JWT authentication.', $route)
                );
            }
        }
    }
}
