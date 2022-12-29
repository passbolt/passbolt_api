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
 * @since         2.11.0
 */
namespace App\Middleware;

use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ContentSecurityPolicyMiddleware implements MiddlewareInterface
{
    /**
     * Add Content Security Policy to the response headers
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     * @return \Psr\Http\Message\ResponseInterface The response.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $response = $handler->handle($request);

        $cspFromConfig = Configure::read('passbolt.security.csp');

        // No CSP - should be for testing only
        if ($cspFromConfig === false) {
            return $response;
        }

        $defaultCsp = "default-src 'self'; ";
        $defaultCsp .= "script-src 'self'; "; // eval needed by canjs for templates
        $defaultCsp .= "style-src 'self' 'unsafe-inline'; "; // inline needed to perform extension iframe resizing
        $defaultCsp .= "img-src 'self';";
        $defaultCsp .= "frame-src 'self' https://*.duosecurity.com;";

        if ($cspFromConfig === null || $cspFromConfig === true) {
            $csp = $defaultCsp;
        } elseif (is_string($cspFromConfig)) {
            $csp = $cspFromConfig;
        } else {
            throw new InternalErrorException('The CSP policy defined in settings is invalid.');
        }

        $response = $response->withAddedHeader('Content-Security-Policy', $csp);

        return $response;
    }
}
