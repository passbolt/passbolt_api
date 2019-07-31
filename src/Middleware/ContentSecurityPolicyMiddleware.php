<?php
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
use Cake\Http\Response;
use Cake\Http\ServerRequest;

class ContentSecurityPolicyMiddleware extends \Cake\Http\Middleware\CsrfProtectionMiddleware
{

    /**
     * Add Content Security Policy to the response headers
     *
     * @param \Cake\Http\ServerRequest $request The request.
     * @param \Cake\Http\Response $response The response.
     * @param callable $next Callback to invoke the next middleware.
     * @return \Cake\Http\Response A response
     */
    public function __invoke(ServerRequest $request, Response $response, $next)
    {
        $cspFromConfig = Configure::read('passbolt.security.csp');

        // No CSP - should be for testing only
        if ($cspFromConfig === false) {
            return $next($request, $response);
        }

        $defaultCsp = "default-src 'self'; ";
        $defaultCsp .= "script-src 'self' 'unsafe-eval'; "; // eval needed by canjs for templates
        $defaultCsp .= "style-src 'self' 'unsafe-inline'; "; // inline needed to perform extension iframe resizing
        $defaultCsp .= "img-src 'self';";
        $defaultCsp .= "frame-src 'self';";

        if ($cspFromConfig === null || $cspFromConfig === true) {
            $csp = $defaultCsp;
        } elseif (is_string($cspFromConfig)) {
            $csp = $cspFromConfig;
        } else {
            throw new InternalErrorException(__('The CSP policy defined in settings is invalid.'));
        }

        $response = $response->withAddedHeader('Content-Security-Policy', $csp);

        return $next($request, $response);
    }
}
