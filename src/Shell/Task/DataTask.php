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
namespace PassboltTestData\Shell\Task;

use Cake\Console\Shell;
use Cake\Core\Configure;

/**
 * Data Installer shell command.
 */
class DataTask extends Shell
{
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
        $parser
            ->setDescription(__('Populate the database with dummy data test.'))
            ->addArgument('scenario', [
                'help' => 'The scenario to play.',
                'required' => true,
                'choices' => array_keys(Configure::read('PassboltTestData.scenarios'))
            ]);

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
        $startTime = time();

        foreach ($shellTtasks as $shellTask) {
            if (Configure::read('passbolt.edition') === 'ce') {
                if (strpos($shellTask, 'PassboltTestData.Pro') !== false) {
                    continue;
                }
            }
            try {
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
            } catch (\Exception $exception) {
                var_dump($exception->getMessage());
                $this->out(__('Could not load task {0}, skipping.', $shellTask));
            }
        }

        $endTime = time();
        $dtF = new \DateTime("@$startTime");
        $dtT = new \DateTime("@$endTime");
        $diff = $dtF->diff($dtT)->format('%im %ss');
        $this->out('<success>' . __('Data inserted successfully in ' . $diff) . '</success>');

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
