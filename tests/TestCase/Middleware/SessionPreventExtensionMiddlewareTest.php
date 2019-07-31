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

namespace App\Test\TestCase\Middleware;

use App\Middleware\SessionPreventExtensionMiddleware;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;

/**
 * Test for SessionPreventExtensionMiddleware
 */
class SessionPreventExtensionMiddlewareTest extends TestCase
{
    /**
     * Ensure the middleware stores the time a request is made while requesting controller which does not prevent the session extension.
     * ie. $_SESSION['SessionPreventExtensionMiddleware']['time'] should contain the latest accessed time.
     *
     * @return void
     */
    public function testSessionPreventExtensionMiddleware_StoreSessionAccessTimeWhenNotPreventingSessionExtension()
    {
        $request = new ServerRequest();
        $request = $request->withAttribute('params', ['controller' => 'AuthLoginController', 'action' => 'loginGet']);
        $next = function ($req, $res) {
            // nothing to do on next
        };

        $middleware = new SessionPreventExtensionMiddleware();
        $middleware($request, new Response(), $next);

        // The time of the request is stored in session as new middleware session time reference
        $this->assertNotNull($_SESSION['SessionPreventExtensionMiddleware']['time']);
        // The Cakephp time reference is not altered by the middleware
        $this->assertArrayNotHasKey('Config', $_SESSION);
    }

    /**
     * Ensure the middleware does not extend the session while requesting /auth/is-authenticated
     * - The middleware session time reference is not altered with the current request time.
     * - The Cakephp time reference is altered with the previously stored middleware session time reference.
     *
     * @return void
     */
    public function testSessionPreventExtensionMiddleware_ReuseSessionAccessTimeWhenNotPreventingSessionExtension()
    {
        $request = new ServerRequest();
        $request = $request->withAttribute('params', ['controller' => 'AuthIsAuthenticated', 'action' => 'isAuthenticated']);
        // Insert a fake time reference in session.
        $requestSession = $request->getSession();
        $timeReference = 1234;
        $requestSession->write(['SessionPreventExtensionMiddleware.time' => $timeReference]);
        $next = function ($req, $res) {
            // nothing to do on next
        };

        $middleware = new SessionPreventExtensionMiddleware();
        $middleware($request, new Response(), $next);

        // The middleware session time reference is not altered with the current request time.
        $this->assertEquals($timeReference, $_SESSION['SessionPreventExtensionMiddleware']['time']);
        // The Cakephp time reference is altered with the previously stored middleware session time reference.
        $this->assertEquals($timeReference, $_SESSION['Config']['time']);
    }
}
