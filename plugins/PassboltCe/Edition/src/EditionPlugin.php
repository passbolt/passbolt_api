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
 * @since         5.13.0
 */
namespace Passbolt\Edition;

use App\Middleware\SetUserIdentityInRequestMiddleware;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Passbolt\Edition\Middleware\LogoutUsersOnEditionChangeMiddleware;
use Passbolt\Edition\Notification\Email\EditionRedactorPool;
use Passbolt\Edition\Service\Healthcheck\Application\EditionPresentInDatabaseApplicationHealthcheck;

class EditionPlugin extends BasePlugin
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
    public function services(ContainerInterface $container): void
    {
        parent::services($container);

        $container->add(EditionPresentInDatabaseApplicationHealthcheck::class);
        $container
            ->extend(HealthcheckServiceCollector::class)
            ->addMethodCall('addService', [EditionPresentInDatabaseApplicationHealthcheck::class]);
    }

    /**
     * @inheritDoc
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        // Position the edition-change logout check right after the identity
        // middleware: the request identity must already be hydrated to a User
        // entity (with last_logged_in) before we can compare timestamps.
        return $middlewareQueue->insertAfter(
            SetUserIdentityInRequestMiddleware::class,
            LogoutUsersOnEditionChangeMiddleware::class,
        );
    }

    /**
     * @param \Cake\Core\PluginApplicationInterface $app App.
     * @return void
     */
    private function registerListeners(PluginApplicationInterface $app): void
    {
        $app->getEventManager()->on(new EditionRedactorPool());
    }
}
