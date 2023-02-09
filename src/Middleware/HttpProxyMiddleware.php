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
 * @since         3.10.0
 */
namespace App\Middleware;

use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HttpProxyMiddleware implements MiddlewareInterface
{
    public const PASSBOLT_SECURITY_PROXIES_ACTIVE_CONFIG_NAME = 'passbolt.security.proxies.active';
    public const PASSBOLT_SECURITY_PROXIES_TRUSTED_PROXIES_CONFIG_NAME = 'passbolt.security.proxies.trustedProxies';
    public const ACCESS_CONTROL_EXPOSE_HEADERS = 'Access-Control-Expose-Headers';
    public const HTTP_HEADERS_WHITELIST = [
        'X-Forwarded-For',
        'X-Real-IP',
        'Client-IP',
    ];

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     * @return \Psr\Http\Message\ResponseInterface The response.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        /** @var \Cake\Http\ServerRequest $request */
        $this->setupServerRequestFromConfig($request);

        /** @var \Cake\Http\Response $response */
        $response = $handler->handle($request);

        return $this->setupResponseHeader($response);
    }

    /**
     * @param \Cake\Http\ServerRequest $request The server request.
     * @return void
     */
    private function setupServerRequestFromConfig(ServerRequest $request): void
    {
        if (Configure::read(self::PASSBOLT_SECURITY_PROXIES_ACTIVE_CONFIG_NAME)) {
            $trustedProxiesArray = Configure::read(self::PASSBOLT_SECURITY_PROXIES_TRUSTED_PROXIES_CONFIG_NAME);
            $request->setTrustedProxies($trustedProxiesArray);
        }
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response The server response.
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function setupResponseHeader(ResponseInterface $response): ResponseInterface
    {
        if (Configure::read(self::PASSBOLT_SECURITY_PROXIES_ACTIVE_CONFIG_NAME)) {
            return $response->withAddedHeader(self::ACCESS_CONTROL_EXPOSE_HEADERS, self::HTTP_HEADERS_WHITELIST);
        }

        return $response;
    }
}
