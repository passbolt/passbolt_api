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
 * @since         5.12.0
 */

namespace App\Middleware;

use Cake\Http\Cookie\CookieCollection;
use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PreventSetCookieOnAvatarsViewMiddleware implements MiddlewareInterface
{
    /**
     * Strip every Set-Cookie emitter path on the avatars view endpoint.
     *
     * The avatar view endpoint is public and has no functional need to update client cookie state.
     * Safari processes Set-Cookie on <img> responses natively, which overwrites the user's active
     * session cookie when the extension UI renders custom avatars. Three sources emit Set-Cookie
     * on CakePHP responses and we clear all three here:
     *   1. PSR-7 response headers — via `$response->withoutHeader('Set-Cookie')`.
     *   2. Response CookieCollection (e.g. CSRF cookie, emitted via `setcookie()`) — reset.
     *   3. PHP SAPI's native header buffer (the session cookie queued by `session_start()`) —
     *      cleared via `header_remove('Set-Cookie')`.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     * @return \Psr\Http\Message\ResponseInterface The response.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $response = $handler->handle($request);

        if (!headers_sent()) {
            header_remove('Set-Cookie');
        }

        if ($response instanceof Response) {
            $response = $response->withCookieCollection(new CookieCollection());
        }

        return $response->withoutHeader('Set-Cookie');
    }
}
