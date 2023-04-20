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

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;

class CleanupCommand extends PassboltCommand
{
    /**
     * @var array|null The list of cleanup jobs to perform.
     */
    private static $cleanups = null;

    /**
     * @var array The list of default cleanup jobs to perform.
     */
    private static $defaultCleanups = [
        'GroupsUsers' => [
            'Soft Deleted Users',
            'Hard Deleted Users',
            'Soft Deleted Groups',
            'Hard Deleted Groups',
            'Duplicated Groups Users',
        ],
        'Favorites' => [
            'Soft Deleted Users',
            'Hard Deleted Users',
            'Soft Deleted Resources',
            'Hard Deleted Resources',
            'Duplicated Favorites',
        ],
        'Comments' => [
            'Soft Deleted Users',
            'Hard Deleted Users',
            'Soft Deleted Resources',
            'Hard Deleted Resources',
        ],
        'Permissions' => [
            'Soft Deleted Users',
            'Hard Deleted Users',
            'Soft Deleted Groups',
            'Hard Deleted Groups',
            'Soft Deleted Resources',
            'Hard Deleted Resources',
            'Duplicated Permissions',
        ],
        'Secrets' => [
            'Soft Deleted Users',
            'Hard Deleted Users',
            'Soft Deleted Resources',
            'Hard Deleted Resources',
            'Hard Deleted Permissions',
        ],
        'Resources' => [
            'Missing ResourceType Id',
        ],
        'Avatars' => [
            'Soft Deleted Users',
            'Hard Deleted Users',
            'Hard Deleted Profiles',
        ],
    ];

    /**
     * Add cleanups jobs.
     *
     * @param array $cleanups The cleanups jobs to add
     * [
     *   MODEL_NAME => List of jobs to perform on this model,
     *   ...
     * ]
     * @return void
     */
    public static function addCleanups(array $cleanups): void
    {
        if (!isset(self::$cleanups)) {
            self::resetCleanups();
        }

        foreach ($cleanups as $modelName => $modelCleanups) {
            if (!array_key_exists($modelName, self::$cleanups)) {
                self::$cleanups[$modelName] = [];
            }
            self::$cleanups[$modelName] = array_merge(self::$cleanups[$modelName], $cleanups[$modelName]);
        }
    }

    /**
     * When running tests, a plugin might modify the list of cleanups, leading
     * to errors in the following tests. This methods helps setting the list of
     * cleanups to its default set of values.
     *
     * @return void
     */
    public static function resetCleanups(): void
    {
        self::$cleanups = self::$defaultCleanups;
    }

    /**
     * Initializes the Shell
     * acts as constructor for subclasses
     * allows configuration of tasks prior to shell execution
     *
     * @return void
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#Cake\Console\ConsoleOptionParser::initialize
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Users');
        $this->loadModel('Roles');
        $this->loadModel('Resources');
        $this->loadModel('GroupsUsers');
        $this->loadModel('Permissions');
        $this->loadModel('AuthenticationTokens');

        if (!isset(self::$cleanups)) {
            self::resetCleanups();
        }
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('Cleanup and fix issues in database.'))
            ->addOption('dry-run', [
                'help' => 'Don\'t fix only display report',
                'default' => 'true',
                'boolean' => true,
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $io->out(' Cleanup shell', 0);
        $dryRun = false;
        if ($args->getOption('dry-run')) {
            $dryRun = true;
            $io->out(' (dry-run)');
        } else {
            $io->out(' (fix mode)');
        }
        $io->hr();

        $totalErrorCount = 0;
        foreach (self::$cleanups as $tableName => $tableCleanup) {
            $table = TableRegistry::getTableLocator()->get($tableName);
            foreach ($tableCleanup as $i => $cleanupName) {
                $timeStart = microtime(true);
                $cleanupMethod = 'cleanup' . str_replace(' ', '', $cleanupName);

                $io->verbose("Clean up start: $tableName:$cleanupMethod");
                $recordCount = $table->{$cleanupMethod}($dryRun);
                $totalErrorCount += $recordCount;
                if ($recordCount) {
                    $cleanupName = strtolower($cleanupName);
                    if ($dryRun) {
                        $io->out(__('{0} issues found in table {1} ({2})', $recordCount, $tableName, $cleanupName));
                    } else {
                        $io->out(__('{0} issues fixed in table {1} ({2})', $recordCount, $tableName, $cleanupName));
                    }
                }

                $timeEnd = microtime(true);
                $timeExecuted = round($timeEnd - $timeStart, 2);
                $io->verbose("Cleanup up end ({$timeExecuted}s): $tableName:$cleanupMethod");
            }
        }

        if ($totalErrorCount) {
            if ($dryRun) {
                $msg = __('{0} issues detected, please re-run without --dry-run to fix.', $totalErrorCount);
                $io->out($msg);
            } else {
                $io->out(__('{0} issues fixed!', $totalErrorCount));
            }
        } else {
            $io->out(__('No issue found, data looks squeaky clean!'));
        }

        return $this->successCode();
    }
}
