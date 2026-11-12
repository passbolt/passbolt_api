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
 * @since         5.18.0
 */
namespace App\Middleware;

use Cake\Core\Configure;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PreventHttpMethodOverrideMiddleware implements MiddlewareInterface
{
    public const PASSBOLT_SECURITY_PREVENT_HTTP_METHOD_OVERRIDE = 'passbolt.security.preventHttpMethodOverride';

    /**
     * Rejects requests whose method was overridden via `_method` or `X-Http-Method-Override`.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler.
     * @return \Psr\Http\Message\ResponseInterface A response.
     * @throws \Cake\Http\Exception\MethodNotAllowedException if the method differs from the original one.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (Configure::read(self::PASSBOLT_SECURITY_PREVENT_HTTP_METHOD_OVERRIDE) === false) {
            return $handler->handle($request);
        }

        if (!$request instanceof ServerRequest) {
            return $handler->handle($request);
        }

        $originalMethod = $request->getEnv('ORIGINAL_REQUEST_METHOD');
        if ($originalMethod === null) {
            return $handler->handle($request);
        }

        $effectiveMethod = $request->getMethod();
        if (strcasecmp($originalMethod, $effectiveMethod) === 0) {
            return $handler->handle($request);
        }

        throw new MethodNotAllowedException(__('HTTP method override is not allowed.'));
    }
}
