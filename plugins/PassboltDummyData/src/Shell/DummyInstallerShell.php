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
namespace PassboltDummyData\Shell;

use Cake\Core\Configure;
use PassboltData\Shell\DataInstallerShell;

/**
 * Installer shell command.
 */
class DummyInstallerShell extends DataInstallerShell
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
            'choices' => array_keys(Configure::read('PassboltDummyData.scenarios'))
        ])->setDescription(__('Populate the database with dummy data test.'));
        return $parser;
    }

    /**
     * Get the tasks to execute.
     * @return array
     */
    protected function _getShellTasks()
    {
        $scenarios = Configure::read('PassboltDummyData.scenarios');
        $scenario = $scenarios[$this->args[0]];
        return $scenario['install']['shellTasks'];
    }
}
