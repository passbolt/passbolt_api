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

/**
 * Fixturize shell command.
 */
class FixturizeShell extends Shell
{

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main()
    {
        $shellTasks = Configure::read('PassboltTestData.scenarios.' . $this->args[0] . '.fixturize.shellTasks');
        if (!is_null($shellTasks)) {
            foreach ($shellTasks as $shellTask) {
                $task = $this->Tasks->load($shellTask);
                $fixtureName = $this->_getFixtureName($task);
                $Model = $this->loadModel($task->entityName);
                $tableName = $Model->getTable();
                $this->dispatchShell("bake fixture $fixtureName -r -s -n 10000 --table $tableName -f");
            }
        }
    }

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addArgument('scenario', [
            'help' => 'The scenario to fixturize.',
            'required' => true,
            'choices' =>  array_keys(Configure::read('PassboltTestData.scenarios')),
        ])->setDescription(__('Fixturize a dummy scenario.'));
        return $parser;
    }

    /**
     * Get the name of the fixture for a given shell task.
     * @return array
     */
    protected function _getFixtureName($shellTask)
    {
        if (isset($shellTask->fixtureName)) {
            return $shellTask->fixtureName;
        }
        return $shellTask->entityName;
    }
}
