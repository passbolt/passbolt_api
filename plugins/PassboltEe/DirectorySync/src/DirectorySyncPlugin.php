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
 * @since         3.8.0
 */
namespace Passbolt\DirectorySync;

use App\Service\Command\ProcessUserService;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Resources\ResourcesExpireResourcesServiceInterface;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Passbolt\DirectorySync\Command\AllCommand;
use Passbolt\DirectorySync\Command\DebugCommand;
use Passbolt\DirectorySync\Command\DirectorySyncCommand;
use Passbolt\DirectorySync\Command\GroupsCommand;
use Passbolt\DirectorySync\Command\IgnoreCreateCommand;
use Passbolt\DirectorySync\Command\IgnoreDeleteCommand;
use Passbolt\DirectorySync\Command\IgnoreListCommand;
use Passbolt\DirectorySync\Command\TestCommand;
use Passbolt\DirectorySync\Command\UsersCommand;
use Passbolt\DirectorySync\Service\Healthcheck\DirectorySyncEndpointsDisabledHealthcheck;
use Passbolt\DirectorySync\Service\Healthcheck\SslVerifyPeerDirectorySyncHealthcheck;

class DirectorySyncPlugin extends BasePlugin
{
    public const PLUGIN_CONFIG_PATH = PLUGINS . 'PassboltEe' . DS . 'DirectorySync' . DS . 'config' . DS;

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(AllCommand::class)
            ->addArguments([ProcessUserService::class, ResourcesExpireResourcesServiceInterface::class]);
        $container->add(DebugCommand::class)->addArgument(ProcessUserService::class);
        $container->add(DirectorySyncCommand::class)->addArgument(ProcessUserService::class);
        $container->add(GroupsCommand::class)
            ->addArguments([ProcessUserService::class, ResourcesExpireResourcesServiceInterface::class]);
        $container->add(IgnoreCreateCommand::class)->addArgument(ProcessUserService::class);
        $container->add(IgnoreDeleteCommand::class)->addArgument(ProcessUserService::class);
        $container->add(IgnoreListCommand::class)->addArgument(ProcessUserService::class);
        $container->add(TestCommand::class)->addArgument(ProcessUserService::class);
        $container->add(UsersCommand::class)
            ->addArguments([ProcessUserService::class, ResourcesExpireResourcesServiceInterface::class]);

        // Directory sync health checks
        $container->add(DirectorySyncEndpointsDisabledHealthcheck::class);
        $container->add(SslVerifyPeerDirectorySyncHealthcheck::class);
        // Add directory sync health check services to collector
        $container
            ->extend(HealthcheckServiceCollector::class)
            ->addMethodCall('addService', [DirectorySyncEndpointsDisabledHealthcheck::class])
            ->addMethodCall('addService', [SslVerifyPeerDirectorySyncHealthcheck::class]);
    }
}
