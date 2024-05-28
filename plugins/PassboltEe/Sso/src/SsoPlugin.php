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
 * @since         3.9.0
 */
namespace Passbolt\Sso;

use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Passbolt\Sso\Notification\Email\SsoSettingsRedactorPool;
use Passbolt\Sso\Notification\Email\SsoStage2RedactorPool;
use Passbolt\Sso\Service\Healthcheck\SslHostVerificationSsoHealthcheck;

class SsoPlugin extends BasePlugin
{
    use FeaturePluginAwareTrait;

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        $this->registerListeners($app);
    }

    /**
     * Register Sso related listeners.
     *
     * @param \Cake\Core\PluginApplicationInterface $app App
     * @return void
     */
    public function registerListeners(PluginApplicationInterface $app): void
    {
        // Register email redactors
        $app
            ->getEventManager()
            ->on(new SsoSettingsRedactorPool())
            ->on(new SsoStage2RedactorPool());
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        // SSO Health checks
        $container->add(SslHostVerificationSsoHealthcheck::class);
        // Add SSO health check services to collector
        $container
            ->extend(HealthcheckServiceCollector::class)
            ->addMethodCall('addService', [SslHostVerificationSsoHealthcheck::class]);
    }
}
