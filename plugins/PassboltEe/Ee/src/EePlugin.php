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
 * @since         3.2.0
 */
namespace Passbolt\Ee;

use App\Service\Setup\AbstractRecoverStartService;
use App\Service\Setup\AbstractSetupStartService;
use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Passbolt\AccountRecovery\Service\Setup\RecoverStartAccountRecoveryInfoService;
use Passbolt\AccountRecovery\Service\Setup\SetupStartAccountRecoveryInfoService;
use Passbolt\Ee\Command\SubscriptionCheckCommand;
use Passbolt\Ee\Command\SubscriptionImportCommand;
use Passbolt\Ee\Service\AccountRecoveryContinue\AccountRecoveryContinueAggregatorService;
use Passbolt\Ee\Service\Setup\EeRecoverStartService;
use Passbolt\Ee\Service\Setup\EeSetupStartService;
use Passbolt\UserPassphrasePolicies\Service\AccountRecovery\AccountRecoveryContinueUserPassphrasePoliciesService;
use Passbolt\UserPassphrasePolicies\Service\Setup\RecoverStartUserPassphrasePoliciesInfoService;
use Passbolt\UserPassphrasePolicies\Service\Setup\SetupStartUserPassphrasePoliciesInfoService;
use Psr\Container\ContainerInterface;

class EePlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function console($commands): CommandCollection
    {
        // Alias license_check to subscription_check for retro compatibility
        $commands->add('passbolt license_check', SubscriptionCheckCommand::class);
        $commands->add('passbolt subscription_check', SubscriptionCheckCommand::class);
        $commands->add('passbolt subscription_import', SubscriptionImportCommand::class);

        return $commands;
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->extend(AbstractSetupStartService::class)
            ->setConcrete(EeSetupStartService::class)
            ->addArguments([
                SetupStartAccountRecoveryInfoService::class,
                SetupStartUserPassphrasePoliciesInfoService::class,
            ]);

        $container->extend(AbstractRecoverStartService::class)
            ->setConcrete(EeRecoverStartService::class)
            ->addArguments([
                RecoverStartAccountRecoveryInfoService::class,
                RecoverStartUserPassphrasePoliciesInfoService::class,
            ]);

        $container
            ->add(AccountRecoveryContinueAggregatorService::class)
            ->addArgument(AccountRecoveryContinueUserPassphrasePoliciesService::class);
    }
}
