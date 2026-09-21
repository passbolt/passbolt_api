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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode;

use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventManager;
use Passbolt\OfflineMode\Event\OfflineItemsResourceDeleteListener;
use Passbolt\OfflineMode\Event\OfflineItemsSecretDeleteListener;
use Passbolt\OfflineMode\Event\OfflineItemsSettingsDeleteListener;
use Passbolt\OfflineMode\Event\OfflineItemsUserDeleteListener;
use Passbolt\OfflineMode\Event\OfflineResourceIndexListener;
use Passbolt\OfflineMode\Form\OfflineSettingsDefaultsForm;
use Passbolt\OfflineMode\Form\OfflineSettingsFormInterface;
use Passbolt\OfflineMode\Notification\Email\OfflineModeSettingsRedactorPool;
use Passbolt\OfflineMode\Service\Settings\OfflineSettingsGetService;
use Passbolt\OfflineMode\Service\Settings\OfflineSettingsSetService;

class OfflineModePlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
        Configure::write('passbolt.plugins.offlineMode.isInBeta', true);
        $this->attachListeners(EventManager::instance());
    }

    /**
     * Attach the OfflineMode event listeners (email redactor pool + future listeners).
     *
     * @param \Cake\Event\EventManager $eventManager EventManager.
     * @return void
     */
    public function attachListeners(EventManager $eventManager): void
    {
        $eventManager
            ->on(new OfflineModeSettingsRedactorPool())
            ->on(new OfflineResourceIndexListener())
            ->on(new OfflineItemsSettingsDeleteListener())
            ->on(new OfflineItemsUserDeleteListener())
            ->on(new OfflineItemsResourceDeleteListener())
            ->on(new OfflineItemsSecretDeleteListener());
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(OfflineSettingsFormInterface::class)
            ->setConcrete(OfflineSettingsDefaultsForm::class);

        $container->add(OfflineSettingsGetService::class)
            ->addArgument(OfflineSettingsFormInterface::class);
        $container->add(OfflineSettingsSetService::class)
            ->addArgument(OfflineSettingsFormInterface::class);
    }
}
