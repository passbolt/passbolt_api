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
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventManager;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\RoutingMiddleware;
use Passbolt\JwtAuthentication\Middleware\JwtAuthDetectionMiddleware;
use Passbolt\JwtAuthentication\Middleware\JwtCsrfDetectionMiddleware;
use Passbolt\JwtAuthentication\Middleware\JwtDestroySessionMiddleware;
use Passbolt\JwtAuthentication\Middleware\JwtRouteFilterMiddleware;
use Passbolt\JwtAuthentication\Notification\Email\Redactor\JwtAuthenticationEmailRedactorPool;

class Plugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        $this->initEmails();
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
     * Setup the plugin's email pool.
     *
     * @return void
     */
    protected function initEmails(): void
    {
        EventManager::instance()->on(new JwtAuthenticationEmailRedactorPool());
    }
}
