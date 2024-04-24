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
namespace Passbolt\JwtAuthentication;

use App\Middleware\CsrfProtectionMiddleware;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\RoutingMiddleware;
use Passbolt\JwtAuthentication\Authenticator\JwtArmoredChallengeInterface;
use Passbolt\JwtAuthentication\Authenticator\JwtArmoredChallengeService;
use Passbolt\JwtAuthentication\Event\LogAuthenticationWithNonValidJwtAccessToken;
use Passbolt\JwtAuthentication\Event\RemoveCsrfCookieOnJwt;
use Passbolt\JwtAuthentication\Event\RemoveSessionCookiesOnJwt;
use Passbolt\JwtAuthentication\Event\SetSessionIdentifierOnLogin;
use Passbolt\JwtAuthentication\Middleware\JwtAuthDetectionMiddleware;
use Passbolt\JwtAuthentication\Middleware\JwtCsrfDetectionMiddleware;
use Passbolt\JwtAuthentication\Middleware\JwtDestroySessionMiddleware;
use Passbolt\JwtAuthentication\Middleware\JwtRouteFilterMiddleware;
use Passbolt\JwtAuthentication\Notification\Email\Redactor\JwtAuthenticationEmailRedactorPool;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Service\Healthcheck\DirectoryNotWritableJwtHealthcheck;
use Passbolt\JwtAuthentication\Service\Healthcheck\ValidKeyPairJwtHealthcheck;

class JwtAuthenticationPlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        $this->registerListeners($app);
    }

    /**
     * @inheritDoc
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            ->insertAfter(RoutingMiddleware::class, JwtAuthDetectionMiddleware::class)
            ->insertAfter(JwtAuthDetectionMiddleware::class, JwtRouteFilterMiddleware::class)
            ->insertBefore(AuthenticationMiddleware::class, JwtDestroySessionMiddleware::class)
            ->insertBefore(CsrfProtectionMiddleware::class, JwtCsrfDetectionMiddleware::class);

        return $middlewareQueue;
    }

    /**
     * Register JWT related listeners.
     *
     * @param \Cake\Core\PluginApplicationInterface $app App
     * @return void
     */
    public function registerListeners(PluginApplicationInterface $app): void
    {
        $app->getEventManager()
            ->on(new JwtAuthenticationEmailRedactorPool())
            ->on(new LogAuthenticationWithNonValidJwtAccessToken())
            ->on(new RemoveSessionCookiesOnJwt())
            ->on(new RemoveCsrfCookieOnJwt())
            ->on(new SetSessionIdentifierOnLogin());
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(JwtArmoredChallengeInterface::class, JwtArmoredChallengeService::class);
        $container->add(JwksGetService::class);

        $container->add(DirectoryNotWritableJwtHealthcheck::class);
        $container->add(ValidKeyPairJwtHealthcheck::class);
        $container
            ->extend(HealthcheckServiceCollector::class)
            ->addMethodCall('addService', [DirectoryNotWritableJwtHealthcheck::class])
            ->addMethodCall('addService', [ValidKeyPairJwtHealthcheck::class]);
    }
}
