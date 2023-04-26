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
 * @since         3.10.0
 */
namespace Passbolt\SelfRegistration;

use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Passbolt\SelfRegistration\Notification\Email\Redactor\SelfRegistrationEmailRedactorPool;
use Passbolt\SelfRegistration\Notification\Email\Redactor\SelfRegistrationNotificationSettingsDefinition;
use Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationDryRunServiceInterface;
use Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationEmailDomainsDryRunService;

class SelfRegistrationPlugin extends BasePlugin
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
        $container
            ->extend(SelfRegistrationDryRunServiceInterface::class)
            ->setConcrete(SelfRegistrationEmailDomainsDryRunService::class);
    }

    /**
     * Register Self Registration related listeners.
     *
     * @param \Cake\Core\PluginApplicationInterface $app App
     * @return void
     */
    public function registerListeners(PluginApplicationInterface $app): void
    {
        $app->getEventManager()
            ->on(new SelfRegistrationEmailRedactorPool())
            ->on(new SelfRegistrationNotificationSettingsDefinition());
    }
}
