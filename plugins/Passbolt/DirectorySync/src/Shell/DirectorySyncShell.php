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
use Cake\Core\Configure;
use Cake\Routing\Router;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;

class DirectorySyncShell extends AppShell
{
    /**
     * @var array of linked tasks
     */
    public $tasks = [
        'Passbolt/DirectorySync.Test',
        'Passbolt/DirectorySync.All',
        'Passbolt/DirectorySync.Users',
        'Passbolt/DirectorySync.Groups',
        'Passbolt/DirectorySync.IgnoreList',
        'Passbolt/DirectorySync.IgnoreCreate',
        'Passbolt/DirectorySync.IgnoreDelete',
    ];

    /**
     * Initialize.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        if (!$this->assertNotRoot()) {
            $this->abort(__('aborting'));
        }

        $isLdapLoaded = extension_loaded('ldap');
        if (!$isLdapLoaded) {
            $this->abort(__('Error: the ldap extension is not installed'));
        }

        $this->directoryOrgSettings = DirectoryOrgSettings::get();
        if (!$this->directoryOrgSettings->isEnabled()) {
            $this->err(__('The ldap integration is not configured'));
            $this->info(
                __(
                    'To fix this problem, you need to configure ldap: {0}.',
                    [Router::url('/app/administration/users-directory', true)]
                )
            );
            $this->abort(__('aborting'));
        }
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

        $parser = parent::getOptionParser();
        $parser->setDescription(__('The directory shell offer synchronizations tasks from the CLI.'));

        $parser->addSubcommand('test', [
            'help' => __d('cake_console', 'Test ldap connection and objects.'),
            'parser' => $this->All->getOptionParser(),
        ]);
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
            'help' => __d('cake_console', 'List all the ignored record during the directory synchronization process.'),
            'parser' => $this->IgnoreList->getOptionParser(),
        ]);
        $parser->addSubcommand('ignore-create', [
            'help' => __d('cake_console', 'Start ignoring a record during the directory synchronization process.'),
            'parser' => $this->IgnoreCreate->getOptionParser(),
        ]);
        $parser->addSubcommand('ignore-delete', [
            'help' => __d('cake_console', 'Stop ignoring a record during the directory synchronization process.'),
            'parser' => $this->IgnoreDelete->getOptionParser(),
        ]);

        return $parser;
    }
}
