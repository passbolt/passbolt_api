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
namespace App\Shell;
use App\Shell\AppShell;

class PassboltShell extends AppShell
{
    // Found in src/Shell/Task/SoundTask.php
    public $tasks = ['Healthcheck'];

    /**
     * Control what get displayed / what to hide
     *
     * @var array
     */
    protected $_displayOptions = [
        'hide-pass' => false,
        'hide-warning' => false,
        'hide-help' => false,
        'hide-title' => false
    ];

    /**
     * Display the passbolt ascii banner
     *
     * @return void
     */
    protected function _welcome() {
        $this->out();
        $this->out('     ____                  __          ____  ');
        $this->out('    / __ \____  _____ ____/ /_  ____  / / /_ ');
        $this->out('   / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/ ');
        $this->out('  / ____/ /_/ (__  |__  ) /_/ / /_/ / / /    ');
        $this->out(' /_/    \__,_/____/____/_.___/\____/_/\__/   ');
        $this->out('');
        $this->out(' Open source password manager for teams');
        $this->hr();
    }

    /**
     * Get command options parser
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $this->_io->styles('fail', ['text' => 'red', 'blink' => false]);
        $this->_io->styles('success', ['text' => 'green', 'blink' => false]);

        $this->rootNotAllowed();

        $parser = parent::getOptionParser();
        $parser->setDescription(__('The Passbolt CLI offers an access to the passbolt API directly from the console.'));
        $parser->addSubcommand('healthcheck', [
            'help' => __d('cake_console', 'Check the configuration of the passbolt installation and associated environment.'),
            'parser' => $this->Healthcheck->getOptionParser(),
        ]);
        return $parser;
    }

}
