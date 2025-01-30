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
 * @since         4.11.0
 */
namespace App\Middleware;

use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PreventHostHeaderFallbackMiddleware implements MiddlewareInterface
{
    /**
     * Throws a bad request if the full base url is not set, host header is set and header fallback config is set to true.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler.
     * @return \Psr\Http\Message\ResponseInterface A response.
     * @throws \Cake\Http\Exception\BadRequestException if the API version provided is deprecated
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $configPreventHostHeaderFallback = Configure::read('passbolt.security.preventHostHeaderFallback', false);
        if (!$configPreventHostHeaderFallback) {
            return $handler->handle($request);
        }

        $fullBaseUrl = Configure::read('App.fullBaseUrl');
        if (is_string($fullBaseUrl) && $fullBaseUrl !== '') {
            return $handler->handle($request);
        }

        /** @var \Cake\Http\ServerRequest $request */
        $hostHeader = $request->getHeader('Host');
        if (empty($hostHeader)) {
            return $handler->handle($request);
        }

        throw new BadRequestException('Setting host header is not allowed when full base URL is not set.');
    }
}
