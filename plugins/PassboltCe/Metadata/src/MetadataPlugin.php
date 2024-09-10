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
namespace Passbolt\Metadata;

use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventManager;
use Passbolt\Metadata\Command\MigrateResourcesCommand;
use Passbolt\Metadata\Event\MetadataUserDeleteSuccessListener;
use Passbolt\Metadata\Event\SetupCompleteListener;

class MetadataPlugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
        $this->attachListeners(EventManager::instance());
    }

    /**
     * Attach the Locale related event listeners.
     *
     * @param \Cake\Event\EventManager $eventManager EventManager
     * @return void
     */
    public function attachListeners(EventManager $eventManager): void
    {
        $eventManager->on(new SetupCompleteListener());
        $eventManager->on(new MetadataUserDeleteSuccessListener());
    }

    /**
     * @inheritDoc
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        // Alias commands
        $commands->add('passbolt metadata migrate_resources', MigrateResourcesCommand::class);

        return $commands;
    }
}
