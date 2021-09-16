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
 * @since         3.3.0
 */
namespace Passbolt\MultiFactorAuthentication\Middleware;

use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Event\UpdateSessionIdInMfaToken;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MfaRefreshTokenCreatedListenerMiddleware implements MiddlewareInterface
{
    /**
     * Whenever a refresh token is issued,
     * the MFA cookie passed as parameter should be updated with
     * the associated access token.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     * @return \Psr\Http\Message\ResponseInterface The response.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        /** @var \Cake\Http\ServerRequest $request */
        $mfaToken = $request->getCookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        if (!empty($mfaToken)) {
            TableRegistry::getTableLocator()
                ->get('AuthenticationTokens')
                ->getEventManager()
                ->on(new UpdateSessionIdInMfaToken($mfaToken));
        }

        return $handler->handle($request);
    }
}
