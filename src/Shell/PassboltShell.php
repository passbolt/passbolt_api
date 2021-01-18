<?php
declare(strict_types=1);

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
 * @since         2.0.0
 */
namespace App\Shell;

use Cake\Core\Configure;

class PassboltShell extends AppShell
{
    /**
     * @var array of linked tasks
     */
    public $tasks = [
        'Cleanup',
        'Datacheck',
        'DropTables',
        'Healthcheck',
        'Install',
        'KeyringInit',
        'ShowLogs',
        'Migrate',
        'MysqlExport',
        'MysqlImport',
        'RegisterUser',
        'SendTestEmail',
        'Version',
    ];

    /**
     * Initializes the Shell
     * acts as constructor for subclasses
     * allows configuration of tasks prior to shell execution
     *
     * @return void
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#Cake\Console\ConsoleOptionParser::initialize
     */
    public function initialize()
    {
        if (Configure::read('passbolt.plugins.license')) {
            $this->tasks[] = 'Passbolt/License.LicenseCheck';
        }
        parent::initialize();
    }

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

        $parser = parent::getOptionParser();
        $parser->setDescription(__('The Passbolt CLI offers an access to the passbolt API directly from the console.'));

        $parser->addSubcommand('cleanup', [
            'help' => __d('cake_console', 'Identify and fix database relational integrity issues.'),
            'parser' => $this->Cleanup->getOptionParser(),
        ]);

        $parser->addSubcommand('drop_tables', [
            'help' => __d('cake_console', 'Drop all the tables. Dangerous but useful for a full reinstall.'),
            'parser' => $this->DropTables->getOptionParser(),
        ]);

        $parser->addSubcommand('healthcheck', [
            'help' => __d('cake_console', 'Run a healthcheck for this passbolt instance.'),
            'parser' => $this->Healthcheck->getOptionParser(),
        ]);

        $parser->addSubcommand('install', [
            'help' => __d('cake_console', 'Installation shell for the passbolt application.'),
            'parser' => $this->Install->getOptionParser(),
        ]);

        $parser->addSubcommand('keyring_init', [
            'help' => __d('cake_console', 'Init the GnuPG keyring.'),
            'parser' => $this->KeyringInit->getOptionParser(),
        ]);

        if (Configure::read('passbolt.plugins.license')) {
            $parser->addSubcommand('license_check', [
                'help' => __d('cake_console', 'Check the license.'),
                'parser' => $this->LicenseCheck->getOptionParser(),
            ]);
        }

        $parser->addSubcommand('migrate', [
            'help' => __d('cake_console', 'Run database migrations.'),
            'parser' => $this->Migrate->getOptionParser(),
        ]);

        $parser->addSubcommand('mysql_export', [
            'help' => __d('cake_console', 'Utility to export mysql database backups.'),
            'parser' => $this->MysqlExport->getOptionParser(),
        ]);

        $parser->addSubcommand('mysql_import', [
            'help' => __d('cake_console', 'Utility to import mysql database backups.'),
            'parser' => $this->MysqlImport->getOptionParser(),
        ]);

        $parser->addSubcommand('register_user', [
            'help' => __d('cake_console', 'Register a new user.'),
            'parser' => $this->RegisterUser->getOptionParser(),
        ]);

        $parser->addSubcommand('send_test_email', [
            'help' => __d('cake_console', 'Try to send a test email and display debug information.'),
            'parser' => $this->SendTestEmail->getOptionParser(),
        ]);

        $parser->addSubcommand('datacheck', [
            'help' => __d('cake_console', 'Revalidate the data of the passbolt installation.'),
            'parser' => $this->Datacheck->getOptionParser(),
        ]);

        $parser->addSubcommand('show_logs', [
            'help' => __d('cake_console', 'Show application logs.'),
            'parser' => $this->ShowLogs->getOptionParser(),
        ]);

        $parser->addSubcommand('version', [
            'help' => __d('cake_console', 'Provide version number'),
            'parser' => $this->Version->getOptionParser(),
        ]);

        return $parser;
    }
}
