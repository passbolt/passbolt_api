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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MfaWebauthnDelegatedReferrerPolicyMiddleware implements MiddlewareInterface
{
    public const DELEGATED_PAGE_PATH = '/mfa/verify/webauthn/delegated';

    /**
     * The delegated state travels in the query string of the page URL. The application-wide security headers set
     * `same-origin`, which still sends the whole URL on every subresource request the page makes, so the state
     * reaches the access log of the instance. This route sends `no-referrer` instead.
     *
     * The controller cannot do this: the security headers are applied to the response after it returns.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Server request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler Handler.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $response = $handler->handle($request);
        $path = str_replace(['.html', '.json'], '', $request->getUri()->getPath());
        if ($path !== self::DELEGATED_PAGE_PATH) {
            return $response;
        }

        return $response->withHeader('Referrer-Policy', 'no-referrer');
    }
}
