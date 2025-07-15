<?php
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
 * @since         2.0.0
 */
namespace PassboltTestData\Command;

use App\Command\DropTablesCommand;
use App\Command\PassboltCommand;
use Bake\Command\FixtureCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Migrations\Command\MigrationsMigrateCommand;
use PassboltTestData\Lib\DataCommand;

/**
 * Fixturize shell command.
 */
class FixturizeCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Fixturize a dummy scenario.');
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $commands = Configure::read('PassboltTestData.scenarios.' . $args->getArgument('scenario') . '.fixturize.shellTasks');

        if (!is_null($commands)) {
            $this->initDatabase();
            foreach ($commands as $command) {
                $command = new $command();
                $this->insertTask($command);
                $this->fixturizeTask($command, $io);
            }
        }

        return self::CODE_SUCCESS;
    }

    /**
     * Init database
     * @return void
     */
    protected function initDatabase()
    {
        $this->executeCommand(DropTablesCommand::class);
        $this->executeCommand(MigrationsMigrateCommand::class);
    }

    /**
     * Insert the task data
     * @param \PassboltTestData\Lib\DataCommand $command Command
     * @return void
     */
    protected function insertTask(DataCommand $command)
    {
        if (method_exists($command, "beforeExecute")) {
            $command->beforeExecute();
        }
        $this->executeCommand($command);
        if (method_exists($command, "afterExecute")) {
            $command->afterExecute();
        }
    }

    /**
     * Fixturize the task data
     * @param \PassboltTestData\Lib\DataCommand $command Command
     * @return void
     */
    protected function fixturizeTask(DataCommand $command, ConsoleIo $io)
    {
        $Model = $this->fetchTable($command->entityName);
        $tableName = $Model->getTable();
        $fixtureName = $command->entityName;

        $this->executeCommand(FixtureCommand::class, [
            'name' => $fixtureName,
            '--table' => $tableName,
            '--force',
            '--records',
            '--count' => 10000,
            '--connection' => 'default',
        ]);

        // Move the fixture in the right folder.
        $fixtureFileName = $fixtureName . 'Fixture.php';
        $taskNamespace = $this->getTaskNamespace($command);
        $destFixturePath = FIXTURES . $taskNamespace;
        @mkdir($destFixturePath); // phpcs:ignore
        rename(FIXTURES . $fixtureFileName, $destFixturePath . DS . $fixtureFileName);

        // Move Adapt the fixture to take care of the namespace
        $content = file_get_contents($destFixturePath . DS . $fixtureFileName);
        $content = preg_replace("/(namespace App.Test.Fixture)/", "$1\\$taskNamespace", $content);
        file_put_contents($destFixturePath . DS . $fixtureFileName, $content);
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->addArgument('scenario', [
            'help' => 'The scenario to fixturize.',
            'required' => true,
            'choices' => array_keys(Configure::read('PassboltTestData.scenarios')),
        ]);

        return $parser;
    }

    /**
     * Get the folder the fixture will be written in for a given command.
     *
     * @param DataCommand $command task
     * @return string
     */
    protected function getTaskNamespace(DataCommand $command): string
    {
        $breads = explode('\\', get_class($command));

        return $breads[count($breads) - 1];
    }
}
