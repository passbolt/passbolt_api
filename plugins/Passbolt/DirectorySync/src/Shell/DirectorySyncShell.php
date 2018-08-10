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
namespace Passbolt\DirectorySync\Shell;
use App\Shell\AppShell;

class DirectorySyncShell extends AppShell
{
    /**
     * @var array of linked tasks
     */
    public $tasks = [
        'Passbolt/DirectorySync.All',
        'Passbolt/DirectorySync.Users',
        'Passbolt/DirectorySync.Groups',
        'Passbolt/DirectorySync.IgnoreList',
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
        $parser->setDescription(__('The directory shell offer synchronizations tasks from the CLI.'));

        $parser->addSubcommand('all', [
            'help' => __d('cake_console', 'Synchronize users and groups.'),
            'parser' => $this->All->getOptionParser(),
        ]);
        $parser->addSubcommand('users', [
            'help' => __d('cake_console', 'Synchronize users'),
            'parser' => $this->Users->getOptionParser(),
        ]);
        $parser->addSubcommand('groups', [
            'help' => __d('cake_console', 'Synchronize groups'),
            'parser' => $this->Groups->getOptionParser(),
        ]);
        $parser->addSubcommand('ignore-list', [
            'help' => __d('cake_console', 'View, add, remove records ignored from synchronization process.'),
            'parser' => $this->IgnoreList->getOptionParser(),
        ]);

        return $parser;
    }
}
