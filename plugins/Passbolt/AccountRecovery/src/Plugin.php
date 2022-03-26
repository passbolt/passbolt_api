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
 * @since         3.6.0
 */
namespace Passbolt\AccountRecovery;

use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\ORM\TableRegistry;
use Passbolt\AccountRecovery\Event\ContainAccountRecoveryUserSettings;
use Passbolt\AccountRecovery\Event\ContainPendingAccountRecoveryUserSetting;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Notification\AccountRecoveryEmailRedactorPool;
use Passbolt\AccountRecovery\Notification\AccountRecoveryNotificationSettingsDefinition;
use Passbolt\AccountRecovery\ServiceProvider\AccountRecoveryOrganizationPolicyServiceProvider;
use Passbolt\AccountRecovery\ServiceProvider\AccountRecoverySetupServiceProvider;

class Plugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
        $this->registerListeners($app);
        $this->addAssociationsToUsersTable();
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->addServiceProvider(new AccountRecoverySetupServiceProvider());
        $container->addServiceProvider(new AccountRecoveryOrganizationPolicyServiceProvider());
    }

    /**
     * Register Account Recovery related listeners.
     *
     * @param \Cake\Core\PluginApplicationInterface $app App
     * @return void
     */
    public function registerListeners(PluginApplicationInterface $app): void
    {
        $app->getEventManager()
            ->on(new AccountRecoveryEmailRedactorPool())
            ->on(new AccountRecoveryNotificationSettingsDefinition())
            ->on(new ContainAccountRecoveryUserSettings())
            ->on(new ContainPendingAccountRecoveryUserSetting());
    }

    /**
     * Defines additional associations related to the plugin
     *
     * @return void
     */
    public function addAssociationsToUsersTable(): void
    {
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $UsersTable->hasOne('Passbolt/AccountRecovery.AccountRecoveryUserSettings');
        $UsersTable->hasOne('Passbolt/AccountRecovery.AccountRecoveryPrivateKeys');
        $UsersTable->hasOne('PendingAccountRecoveryRequests', [
            'className' => 'Passbolt/AccountRecovery.AccountRecoveryRequests',
            'foreignKey' => 'user_id',
            'conditions' => [
                'PendingAccountRecoveryRequests.status' => AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_PENDING,
            ],
        ]);
    }
}
