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

use App\Middleware\ContainerAwareMiddlewareTrait;
use Authentication\AuthenticationServiceInterface;
use Passbolt\MultiFactorAuthentication\Authenticator\MfaWebauthnDelegatedAuthenticationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MfaWebauthnDelegatedAuthDetectionMiddleware implements MiddlewareInterface
{
    use ContainerAwareMiddlewareTrait;

    public const DELEGATED_PATHS = [
        '/mfa/verify/webauthn/delegated',
        '/mfa/verify/webauthn/delegated/begin',
        '/mfa/verify/webauthn/delegated/finish',
    ];

    /**
     * @inheritDoc
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        if ($this->isDelegatedCeremonyRoute($request)) {
            $this->getContainer($request)
                ->extend(AuthenticationServiceInterface::class)
                ->setConcrete(MfaWebauthnDelegatedAuthenticationService::class);
        }

        return $handler->handle($request);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request Server request.
     * @return bool
     */
    private function isDelegatedCeremonyRoute(ServerRequestInterface $request): bool
    {
        $path = str_replace('.json', '', $request->getUri()->getPath());

        return in_array($path, self::DELEGATED_PATHS, true);
    }
}
