<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace PassboltTestData\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

/**
 * Fixturize shell command.
 */
class FixturizeShell extends Shell
{

    /**
     * main() method.
     *
     * @return bool true if success
     */
    public function main()
    {
        $shellTasks = Configure::read('PassboltTestData.scenarios.' . $this->args[0] . '.fixturize.shellTasks');

        if (!is_null($shellTasks)) {
            $this->_initDatabase();
            foreach ($shellTasks as $taskName) {
                $task = $this->Tasks->load($taskName);
                $this->_insertTask($task);
                $this->_fixturizeTask($task);
            }
        }

        return true;
    }

    /**
     * Init database
     * @return void
     */
    protected function _initDatabase()
    {
        $dropTableCmd = 'passbolt drop_tables';
        $this->dispatchShell($dropTableCmd);
        $migrateCmd = 'migrations migrate';
        $this->dispatchShell($migrateCmd);
    }

    /**
     * Insert the task data
     * @param \Cake\Console\Shell $task task
     * @return void
     */
    protected function _insertTask($task)
    {
        if (method_exists($task, "beforeExecute")) {
            $task->beforeExecute();
        }
        $task->execute();
        if (method_exists($task, "afterExecute")) {
            $task->afterExecute();
        }
    }

    /**
     * Fixturize the task data
     * @param \Cake\Console\Shell $task task
     * @return void
     */
    protected function _fixturizeTask($task)
    {
        $Model = $this->loadModel($task->entityName);
        $tableName = $Model->getTable();
        $fixtureName = $task->entityName;
        $this->dispatchShell("bake fixture $fixtureName -r -n 10000 --table $tableName -f");

        // Move the fixture in the right folder.
        $fixtureFileName = $fixtureName . 'Fixture.php';
        $taskNamespace = $this->_getTaskNamespace($task);
        $destFixturePath = FIXTURES . DS . $taskNamespace;
        @mkdir($destFixturePath);
        rename(FIXTURES . DS . $fixtureFileName, $destFixturePath . DS . $fixtureFileName);

        // Move Adapt the fixture to take care of the namespace
        $content = file_get_contents($destFixturePath . DS . $fixtureFileName);
        $content = preg_replace("/(namespace App.Test.Fixture)/", "$1\\$taskNamespace", $content);
        file_put_contents($destFixturePath . DS . $fixtureFileName, $content);
    }

    /**
     * Gets the option parser instance and configures it.
     *
     * By overriding this method you can configure the ConsoleOptionParser before returning it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addArgument('scenario', [
            'help' => 'The scenario to fixturize.',
            'required' => true,
            'choices' => array_keys(Configure::read('PassboltTestData.scenarios')),
        ])->setDescription(__('Fixturize a dummy scenario.'));

        return $parser;
    }

    /**
     * Get the folder the fixutre will be written in for a given shell task.
     *
     * @param \Cake\Console\Shell $shellTask task
     * @return string
     */
    protected function _getTaskNamespace($shellTask)
    {
        $r = new \ReflectionClass($shellTask);
        $breads = explode('\\', $r->getNamespaceName());

        return $breads[count($breads) - 1];
    }
}
