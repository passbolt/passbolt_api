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

use App\Middleware\ContainerAwareMiddlewareTrait;
use Cake\Core\ContainerInterface;
use Passbolt\JwtAuthentication\Authenticator\JwtArmoredChallengeInterface;
use Passbolt\JwtAuthentication\Service\Middleware\JwtRequestDetectionService;
use Passbolt\MultiFactorAuthentication\Authenticator\MfaJwtArmoredChallengeService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class InjectMfaJwtChallengeServiceMiddleware implements MiddlewareInterface
{
    use ContainerAwareMiddlewareTrait;

    /**
     * Inject the appropriate MFA armored challenge interface.
     * When login with JWT and MFA is required, the list of
     * the providers is added to the challenge.
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
        if ($request->getAttribute(JwtRequestDetectionService::IS_JWT_AUTH_REQUEST)) {
            $container = $this->getContainer($request);
            $this->services($container);
        }

        return $handler->handle($request);
    }

    /**
     * Inject the appropriate MFA armored challenge interface.
     * When login with JWT and MFA is required, the list of
     * the providers is added to the challenge.
     *
     * @param \Cake\Core\ContainerInterface $container DIC
     * @return void
     */
    public function services(ContainerInterface $container): void
    {
        $container
            ->extend(JwtArmoredChallengeInterface::class)
            ->setConcrete(MfaJwtArmoredChallengeService::class);
    }
}
