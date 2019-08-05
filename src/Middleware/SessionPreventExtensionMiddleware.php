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

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Utility\Hash;

class SessionPreventExtensionMiddleware
{
    /**
     * Ensure the call to the /auth/is-authenticated route does not extend the
     * user session.
     *
     * The session expiration is handled by 2 mechanisms :
     * - The default php session timeout mechanism. After a defined period of time (session.gc_maxlifetime)
     *   the idle sessions are destroyed. No additional code is required when the sessions are managed by php.
     * - The CakePHP session time control mechanism. CakePHP stores a Config.Time variable in the user session
     *   to save the last time a session has been accessed. This time will be used to destroy the session that
     *   have expired (based on session.gc_maxlifetime). This mechanism is mainly used to handle session expiration
     *   when the session are managed by another system (Cache, Database).
     *
     * In order to avoid a session to be extended while accessing the entry point /auth/is-authenticated.json, this
     * middleware will override the Config.Time variable with the latest time the user has accessed the API on
     * another entry point (value stored previously in the SessionPreventExtensionMiddleware.time session variable).
     *
     * @param \Cake\Http\ServerRequest $request The request.
     * @param \Cake\Http\Response $response The response.
     * @param callable $next Callback to invoke the next middleware.
     * @return \Cake\Http\Response A response
     */
    public function __invoke(ServerRequest $request, Response $response, $next)
    {
        $session = $request->getSession();

        if ($this->shouldSessionExtensionPrevented($request)) {
            $time = $session->read('SessionPreventExtensionMiddleware.time');
            if ($time) {
                $session->write('Config.time', $time);
            }
        } else {
            $session->write('SessionPreventExtensionMiddleware.time', time());
        }

        return $next($request, $response);
    }

    /**
     * Check if the session should not be extented for a given request.
     *
     * @param ServerRequest $request The request.
     * @return bool
     */
    protected function shouldSessionExtensionPrevented(ServerRequest $request)
    {
        $params = $request->getAttribute('params', '');
        $controller = Hash::get($params, 'controller');
        $action = Hash::get($params, 'action');

        return $controller === "AuthIsAuthenticated" && $action === "isAuthenticated";
    }
}
