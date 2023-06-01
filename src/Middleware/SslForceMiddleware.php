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
 * @since         4.1.0
 */
namespace App\Middleware;

use Cake\Core\Configure;
use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SslForceMiddleware implements MiddlewareInterface
{
    public const PASSBOLT_SSL_FORCE_CONFIG_NAME = 'passbolt.ssl.force';

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
        $isNotHttps = $request->getUri()->getScheme() !== 'https';
        $isSslEnforced = Configure::read(self::PASSBOLT_SSL_FORCE_CONFIG_NAME);

        if ($isSslEnforced && $isNotHttps) {
            $url = 'https://' . $request->getEnv('HTTP_HOST') . $request->getEnv('REQUEST_URI');

            return (new Response())
                ->withStatus(302)
                ->withLocation($url);
        }

        /** @var \Cake\Http\Response $response */
        $response = $handler->handle($request);

        // Tell the browser to force HTTPS use
        if ($isSslEnforced) {
            return $response
                ->withHeader('strict-transport-security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
