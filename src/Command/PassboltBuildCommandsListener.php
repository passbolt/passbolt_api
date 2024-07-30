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

namespace App\Command;

use Cake\Console\CommandCollection;
use Cake\Core\Container;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

class PassboltBuildCommandsListener implements EventListenerInterface
{
    protected ?CommandCollection $passboltCommandCollection;

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Console.buildCommands' => 'setPassboltCommandCollection',
            'Application.buildContainer' => 'addCommandCollectionToContainer',
        ];
    }

    /**
     * When the command dispatcher is initialized, collect all the commands
     *
     * @param \Cake\Event\Event $event event triggered when all the commands are collected
     * @param \Cake\Console\CommandCollection $commands collection of commands
     * @return void
     */
    public function setPassboltCommandCollection(Event $event, CommandCollection $commands): void
    {
        $passboltCommands = [];
        foreach ($commands as $name => $commandFQN) {
            if (strpos($name, 'passbolt ') === 0) {
                $subCommand = substr($name, 9);
                $passboltCommands[$subCommand] = $commandFQN;
            }
        }
        ksort($passboltCommands);

        $this->passboltCommandCollection = new CommandCollection($passboltCommands);
    }

    /**
     * Inject the list of all commands in the Passbolt command. This will be usefull
     * to display a clean list of all the passbolt command.
     *
     * @param \Cake\Event\Event $event event triggered when the application DIC is created
     * @param \Cake\Core\Container $container DIC
     * @return void
     */
    public function addCommandCollectionToContainer(Event $event, Container $container): void
    {
        $container
            ->add(PassboltCommand::class)
            ->addArgument($this->passboltCommandCollection);
    }
}
