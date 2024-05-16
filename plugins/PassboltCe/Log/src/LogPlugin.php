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
namespace Passbolt\Log;

use App\Service\Command\ProcessUserService;
use Cake\Core\BasePlugin;
use Cake\Core\ContainerInterface;
use Cake\Core\PluginApplicationInterface;
use Passbolt\Log\Command\ActionLogsPurgeCommand;
use Passbolt\Log\Events\ActionLogsAfterCreateListener;
use Passbolt\Log\Events\ActionLogsBeforeRenderListener;
use Passbolt\Log\Events\ActionLogsModelListener;

class LogPlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
        $app->getEventManager()
            ->on(new ActionLogsAfterCreateListener())
            ->on(new ActionLogsBeforeRenderListener())
            ->on(new ActionLogsModelListener());
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(ActionLogsPurgeCommand::class)
            ->addArgument(ProcessUserService::class);
    }
}
