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
namespace PassboltData\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;

class DataInstallerShell extends Shell
{
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
    }

    /**
     * Get the tasks to execute.
     * @return array
     */
    protected function _getShellTasks()
    {
       return Configure::read('PassboltData.shellTasks');
    }
}
