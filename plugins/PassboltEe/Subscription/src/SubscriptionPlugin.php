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
 * @since         4.10.0
 */
namespace Passbolt\Subscription;

use App\Service\Subscriptions\SubscriptionCheckInCommandServiceInterface;
use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Passbolt\Subscription\Command\SubscriptionCheckCommand;
use Passbolt\Subscription\Command\SubscriptionImportCommand;
use Passbolt\Subscription\Service\Subscriptions\EeSubscriptionCheckInCommandService;
use Psr\Container\ContainerInterface;

class SubscriptionPlugin extends BasePlugin
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
        if ($container->has(SubscriptionCheckInCommandServiceInterface::class)) {
            $container
                ->extend(SubscriptionCheckInCommandServiceInterface::class)
                ->setConcrete(EeSubscriptionCheckInCommandService::class);
            $container->add(SubscriptionCheckCommand::class)->addArguments([
                SubscriptionCheckInCommandServiceInterface::class,
            ]);
        }
    }
}
