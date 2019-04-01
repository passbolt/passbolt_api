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
 * @since         2.8.0
 */
namespace PassboltTestData\Shell;

use App\Shell\AppShell;

/**
 * Dummy shell command.
 */
class DummyShell extends AppShell
{
    /**
     * @var array of linked tasks
     */
    public $tasks = [
        'PassboltTestData.Insert'
    ];

    /**
     * Get command options parser
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $this->_io->styles('fail', ['text' => 'red', 'blink' => false]);
        $this->_io->styles('success', ['text' => 'green', 'blink' => false]);

        $parser = parent::getOptionParser();
        $parser->setDescription(__('The Passbolt CLI offers an access to the passbolt API directly from the console.'));

        $parser->addSubcommand('Insert', [
            'help' => __d('cake_console', 'Insert dummies'),
            'parser' => $this->Insert->getOptionParser(),
        ]);

        return $parser;
    }
}
