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
 * Installer shell command.
 */
class InstallerShell extends Shell
{

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
            'help' => 'The scenario to play.',
            'required' => true,
            'choices' => array_keys(Configure::read('PassboltTestData.scenarios'))
        ])->setDescription(__('Populate the database with dummy data test.'));
        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main()
    {
        $shellTtasks = $this->_getShellTasks();

        foreach ($shellTtasks as $shellTask) {
            $task = $this->Tasks->load($shellTask);
            $task->params['quiet'] = isset($this->params['quiet']) && $this->params['quiet'] == 1 ? 1 : 0;
            $task->params['connection'] = isset($this->params['connection']) ? $this->params['connection'] : 'default';
            if (method_exists($task, "beforeExecute")) {
                $task->beforeExecute();
            }
            $task->execute();
            if (method_exists($task, "afterExecute")) {
                $task->afterExecute();
            }
        }
        return true;
    }

    /**
     * Get the tasks to execute.
     * @return array
     */
    protected function _getShellTasks()
    {
        $scenarios = Configure::read('PassboltTestData.scenarios');
        $scenario = $scenarios[$this->args[0]];
        return $scenario['install']['shellTasks'];
    }
}
