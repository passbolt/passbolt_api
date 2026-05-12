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
 * @since         4.3.0
 */
namespace Passbolt\UserPassphrasePolicies;

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Passbolt\UserPassphrasePolicies\Notification\Email\UserPassphrasePoliciesSettingsRedactorPool;
use Passbolt\UserPassphrasePolicies\Service\AccountRecovery\AccountRecoveryContinueUserPassphrasePoliciesService;
use Passbolt\UserPassphrasePolicies\Service\Setup\RecoverStartUserPassphrasePoliciesInfoService;
use Passbolt\UserPassphrasePolicies\Service\Setup\SetupStartUserPassphrasePoliciesInfoService;

class UserPassphrasePoliciesPlugin extends BasePlugin
{
    use FeaturePluginAwareTrait;

    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        // Register email redactors
        $app->getEventManager()->on(new UserPassphrasePoliciesSettingsRedactorPool());
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(SetupStartUserPassphrasePoliciesInfoService::class);
        $container->add(RecoverStartUserPassphrasePoliciesInfoService::class);
        $container->add(AccountRecoveryContinueUserPassphrasePoliciesService::class);
    }
}
