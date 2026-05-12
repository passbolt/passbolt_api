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
 * @since         5.5.0
 */
namespace Passbolt\Scim\Middleware;

use App\Authenticator\SessionAuthenticationService;
use App\Middleware\ContainerAwareMiddlewareTrait;
use App\Utility\Application\FeaturePluginAwareTrait;
use Authentication\AuthenticationServiceInterface;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\ServerRequest;
use Passbolt\Scim\Authenticator\ScimAuthenticationService;
use Passbolt\Scim\Utility\ScimTools;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * ScimMiddleware class
 */
class ScimAuthMiddleware implements MiddlewareInterface
{
    use ContainerAwareMiddlewareTrait;
    use FeaturePluginAwareTrait;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The handler.
     * @return \Psr\Http\Message\ResponseInterface The response.
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        /** @var \Cake\Http\ServerRequest $request */
        if (!ScimTools::isScimApiRequest($request)) {
            return $handler->handle($request);
        }

        $this->disableFeaturePlugin('JwtAuthentication');
        $container = $this->getContainer($request);
        $this->assertNotSessionAuthenticated($request, $container);
        Configure::write('Scim.settingId', $request->getParam('settingId'));
        $this->services($container);

        return $handler->handle($request);
    }

    /**
     * @param \Cake\Core\ContainerInterface $container
     * @return void
     */
    private function services(ContainerInterface $container): void
    {
        $container
            ->extend(AuthenticationServiceInterface::class)
            ->setConcrete(ScimAuthenticationService::class);
    }

    /**
     * @param \Cake\Http\ServerRequest $request server request
     * @param \Cake\Core\ContainerInterface $container container
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Cake\Http\Exception\ForbiddenException if the user is logged in via session
     */
    private function assertNotSessionAuthenticated(ServerRequest $request, ContainerInterface $container): void
    {
        /** @var \Authentication\AuthenticationServiceInterface $authenticationService */
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        if (!($authenticationService instanceof SessionAuthenticationService)) {
            return;
        }
        $isUserSessionAuthenticated = $authenticationService->authenticate($request)->isValid();
        if ($isUserSessionAuthenticated) {
            throw new ForbiddenException(__('Simultaneous SCIM and session authentication is not permitted.'));
        }
    }
}
