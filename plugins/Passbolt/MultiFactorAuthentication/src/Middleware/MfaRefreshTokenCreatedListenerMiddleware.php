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

use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Passbolt\JwtAuthentication\Service\RefreshToken\RefreshTokenCreateService;
use Passbolt\MultiFactorAuthentication\Service\UpdateMfaTokenSessionIdService;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MfaRefreshTokenCreatedListenerMiddleware implements MiddlewareInterface
{
    /**
     * Mfa Middleware.
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
        $mfa = $request->getCookie(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        if (!empty($mfa)) {
            $this->updateSessionIdInMfaTokenOnRefreshTokenCreation($mfa);
        }

        return $handler->handle($request);
    }

    /**
     * @param string $mfaToken MFA token in the cookie
     * @return void
     */
    protected function updateSessionIdInMfaTokenOnRefreshTokenCreation(string $mfaToken): void
    {
        $AuthenticationsTable = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $AuthenticationsTable->getEventManager()->on(
            RefreshTokenCreateService::REFRESH_TOKEN_CREATED_EVENT,
            function (EventInterface $event) use ($mfaToken) {
                $accessToken = $event->getData(RefreshTokenCreateService::ACCESS_TOKEN_DATA_KEY);
                (new UpdateMfaTokenSessionIdService())->updateSessionId($mfaToken, $accessToken);
            }
        );
    }
}
