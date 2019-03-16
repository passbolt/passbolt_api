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
 * @since         2.0.0
 */
namespace App\Shell\Task;

use App\Shell\AppShell;
use Cake\ORM\TableRegistry;

class CleanupTask extends AppShell
{
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
        parent::initialize();
        $this->loadModel('Users');
        $this->loadModel('Roles');
        $this->loadModel('GroupsUsers');
        $this->loadModel('Permissions');
        $this->loadModel('AuthenticationTokens');
    }

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
        $parser->setDescription(__('Cleanup orphan records in database.'))
            ->addOption('dry-run', [
                'help' => 'Don\'t delete only display report',
                'default' => 'true',
                'boolean' => true,
            ]);

        return $parser;
    }

    /**
     * Main registration task
     *
     * @return bool
     */
    public function main()
    {
        $dryRun = true;
        $cleanups = [
            'GroupsUsers' => [
                'Soft Deleted Users',
                'Hard Deleted Users',
                'Soft Deleted Groups',
                'Hard Deleted Groups'
            ],
            'Favorites' => [
                'Soft Deleted Users',
                'Hard Deleted Users',
                'Soft Deleted Resources',
                'Hard Deleted Resources'
            ],
            'Comments' => [
                'Soft Deleted Users',
                'Hard Deleted Users',
                'Soft Deleted Resources',
                'Hard Deleted Resources'
            ],
            'Permissions' => [
                'Soft Deleted Users',
                'Hard Deleted Users',
                'Soft Deleted Groups',
                'Hard Deleted Groups',
                'Soft Deleted Resources',
                'Hard Deleted Resources'
            ],
            'Secrets' => [
                'Soft Deleted Users',
                'Hard Deleted Users',
                'Soft Deleted Resources',
                'Hard Deleted Resources',
                'Hard Deleted Permissions'
            ]
        ];

        $this->out(' Cleanup shell', 0);
        $dryRun = false;
        if ($this->param('dry-run')) {
            $dryRun = true;
            $this->out(' (dry-run)');
        } else {
            $this->out(' (delete mode)');
        }
        $this->hr();

        $totalErrorCount = 0;
        foreach ($cleanups as $tableName => $tableCleanup) {
            $table = TableRegistry::getTableLocator()->get($tableName);
            foreach ($tableCleanup as $i => $cleanupName) {
                $cleanupMethod = 'cleanup' . str_replace(' ', '', $cleanupName);
                $recordCount = $table->{$cleanupMethod}($dryRun);
                $totalErrorCount += $recordCount;
                if ($recordCount) {
                    $cleanupName = strtolower($cleanupName);
                    if ($dryRun) {
                        $this->out(__('{0} orphan records found in table {1} ({2})', $recordCount, $tableName, $cleanupName));
                    } else {
                        $this->out(__('{0} orphan records deleted in table {1} ({2})', $recordCount, $tableName, $cleanupName));
                    }
                }
            }
        }

        if ($totalErrorCount) {
            if ($dryRun) {
                $this->out(__('{0} issues detected, please run the same command without --dry-run to fix them.', $totalErrorCount));
            } else {
                $this->out(__('{0} issues fixed!', $totalErrorCount));
            }
        } else {
            $this->out(__('No issue found, data looks squeaky clean!'));
        }

        return true;
    }
}
