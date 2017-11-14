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
    /**
     * @var array of linked tasks
     */
    public $tasks = [
        'Healthcheck',
        'Install',
        'DropTables',
        'RegisterUser',
        'PassboltTestData.Data',
        'PassboltTestData.fixturize'
    ];

    /**
     * Display the passbolt ascii banner
     *
     * @return void
     */
    protected function _welcome()
    {
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

        $this->assertNotRoot();

        $parser = parent::getOptionParser();
        $parser->setDescription(__('The Passbolt CLI offers an access to the passbolt API directly from the console.'));

        $parser->addSubcommand('drop_tables', [
            'help' => __d('cake_console', 'Drop all the tables. Dangerous but useful for a full reinstall.'),
            'parser' => $this->DropTables->getOptionParser(),
        ]);

        $parser->addSubcommand('healthcheck', [
            'help' => __d('cake_console', 'Check the configuration of the passbolt installation and associated environment.'),
            'parser' => $this->Healthcheck->getOptionParser(),
        ]);

        $parser->addSubcommand('install', [
            'help' => __d('cake_console', 'Installation shell for the passbolt application.'),
            'parser' => $this->Install->getOptionParser(),
        ]);

        $parser->addSubcommand('register_user', [
            'help' => __d('cake_console', 'Installation shell for the passbolt application.'),
            'parser' => $this->RegisterUser->getOptionParser(),
        ]);

        $parser->addSubcommand('data', [
            'help' => __d('cake_console', 'Installation shell for the passbolt application.'),
            'parser' => $this->Data->getOptionParser(),
        ]);

        return $parser;
    }
}
