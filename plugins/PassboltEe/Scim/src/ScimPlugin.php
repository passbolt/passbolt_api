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
 * @since         4.1.0
 */

namespace Passbolt\Scim;

use App\Service\Healthcheck\HealthcheckServiceCollector;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\RoutingMiddleware;
use Passbolt\JwtAuthentication\Middleware\JwtAuthDetectionMiddleware;
use Passbolt\JwtAuthentication\Service\Middleware\JwtAuthenticationService;
use Passbolt\Scim\Authenticator\ScimAuthenticationService;
use Passbolt\Scim\Middleware\ScimMiddleware;
use Passbolt\Scim\Service\Healthcheck\ScimHealthcheckService;
use Psr\Http\Message\ServerRequestInterface;

class ScimPlugin extends BasePlugin
{
    /**
     * Load all the plugin configuration and bootstrap logic.
     *
     * The host application is provided as an argument. This allows you to load
     * additional plugin dependencies, or attach events.
     *
     * @param \Cake\Core\PluginApplicationInterface $app The host application
     * @return void
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        // SSO Health checks
        $container->add(ScimHealthcheckService::class);
        // Add SSO health check services to collector
        $container
            ->extend(HealthcheckServiceCollector::class)
            ->addMethodCall('addService', [ScimHealthcheckService::class]);
    }

    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            ->insertAfter(RoutingMiddleware::class, ScimMiddleware::class);

        return parent::middleware($middlewareQueue);
    }
}
