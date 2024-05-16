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
 * @since         4.5.0
 */

namespace App\ServiceProvider;

use App\Command\HealthcheckCommand;
use App\Command\InstallCommand;
use App\Command\KeyringInitCommand;
use App\Command\MigrateCommand;
use App\Command\MigratePostgresCommand;
use App\Command\RecoverUserCommand;
use App\Command\RegisterUserCommand;
use App\Service\Command\ProcessUserService;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Subscriptions\DefaultSubscriptionCheckInCommandService;
use App\Service\Subscriptions\SubscriptionCheckInCommandServiceInterface;
use Cake\Core\ContainerInterface;
use Cake\Core\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    protected $provides = [
        ProcessUserService::class,
        SubscriptionCheckInCommandServiceInterface::class,
        HealthcheckCommand::class,
        InstallCommand::class,
        KeyringInitCommand::class,
        MigrateCommand::class,
        RecoverUserCommand::class,
        MigratePostgresCommand::class,
        RegisterUserCommand::class,
    ];

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(ProcessUserService::class);
        $container->add(
            SubscriptionCheckInCommandServiceInterface::class,
            DefaultSubscriptionCheckInCommandService::class
        );

        $container->add(HealthcheckCommand::class)->addArguments([
            ProcessUserService::class,
            HealthcheckServiceCollector::class,
        ]);
        $container->add(InstallCommand::class)->addArguments([
            ProcessUserService::class,
            SubscriptionCheckInCommandServiceInterface::class,
            HealthcheckServiceCollector::class,
        ]);
        $container->add(KeyringInitCommand::class)->addArgument(ProcessUserService::class);
        $container->add(MigrateCommand::class)->addArguments([
            ProcessUserService::class,
            SubscriptionCheckInCommandServiceInterface::class,
        ]);
        $container->add(RecoverUserCommand::class)->addArgument(ProcessUserService::class);
        $container->add(MigratePostgresCommand::class)->addArgument(ProcessUserService::class);
        $container->add(RegisterUserCommand::class)->addArgument(ProcessUserService::class);
    }
}
