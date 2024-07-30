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
 * @since         5.0.0
 */
namespace App\Test\TestCase\Command;

use App\Command\PassboltBuildCommandsListener;
use App\Command\PassboltCommand;
use Cake\Console\CommandCollection;
use Cake\Core\Container;
use Cake\Event\Event;
use Cake\TestSuite\TestCase;
use CakephpFixtureFactories\Command\PersistCommand;
use Migrations\Command\MigrationsCreateCommand;
use PassboltTestData\Command\DummyCommand;
use PassboltTestData\Command\InsertCommand;

class PassboltBuildCommandsListenerTest extends TestCase
{
    /**
     * Ensures that the passbolt commands are correctly filtered form the non-passbolt commands
     * and are sorted alphabetically
     */
    public function testPassboltBuildCommandsListener()
    {
        $listener = new PassboltBuildCommandsListener();
        $commands = new CommandCollection([
            'fixture_factories_persist' => PersistCommand::class,
            'passbolt insert' => InsertCommand::class,
            'passbolt dummy' => DummyCommand::class,
            'migrations create' => MigrationsCreateCommand::class,
        ]);

        $container = new Container();
        $listener->setPassboltCommandCollection(new Event('foo'), $commands);
        $listener->addCommandCollectionToContainer(new Event('bar'), $container);

        /** @var PassboltCommand $passboltCommand */
        $passboltCommand = $container->get(PassboltCommand::class);
        $expectedCommands = new CommandCollection([
            'insert' => InsertCommand::class,
            'dummy' => DummyCommand::class,
        ]);
        $this->assertEquals($expectedCommands, $passboltCommand->getPassboltCommandCollection());
    }
}
