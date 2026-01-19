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

use App\Model\Table\TableCleanupProviderInterface;
use App\Model\Table\UsersTable;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use ReflectionFunction;

/**
 * CleanupCommand class
 */
class CleanupCommand extends PassboltCommand
{
    /**
     * @var array|null The list of cleanup jobs to perform.
     */
    private static ?array $cleanableTables = null;

    /**
     * @var array The list of default cleanup jobs to perform.
     */
    private static array $defaultCleanableTables = [
        'Groups',
        'GroupsUsers',
        'Favorites',
        'Comments',
        'Permissions',
        'Secrets',
        'Resources',
        'Avatars',
        'Users',
    ];

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected UsersTable $Users;

    /**
     * Register tables having cleanup capabilities
     *
     * @param string $cleanableTable The class of the table to cleanup
     * @return void
     */
    public static function registerCleanableTable(string $cleanableTable): void
    {
        if (!isset(self::$cleanableTables)) {
            self::resetCleanups();
        }

        self::$cleanableTables[] = $cleanableTable;
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
        self::$cleanableTables = self::$defaultCleanableTables;
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
        $this->Users = $this->fetchTable('Users');

        if (!isset(self::$cleanableTables)) {
            self::resetCleanups();
        }
    }

    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Identify and fix database relational integrity issues.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser->addOption('dry-run', [
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

        try {
            $this->assertDatabaseState();
        } catch (InternalErrorException $e) {
            $io->warning($e->getMessage());

            return $this->successCode();
        }

        $totalErrorCount = 0;
        foreach (self::$cleanableTables as $cleanableTableName) {
            $table = TableRegistry::getTableLocator()->get($cleanableTableName);
            if (!$table instanceof TableCleanupProviderInterface) {
                continue;
            }
            $tableCleanupCallables = $table->getCleanupMethods();
            foreach ($tableCleanupCallables as $tableCleanupCallable) {
                $timeStart = microtime(true);
                $funcRef = new ReflectionFunction($tableCleanupCallable);
                $cleanupMethodName = $funcRef->getName();
                $cleanupName = $this->methodNameToCleanupName($funcRef->getName());
                $io->verbose("Clean up start: $cleanableTableName:$cleanupMethodName");

                $recordCount = $tableCleanupCallable($dryRun);
                $totalErrorCount += $recordCount;
                if ($recordCount) {
                    if ($dryRun) {
                        $io->out(__(
                            '{0} issues found in table {1} ({2})',
                            $recordCount,
                            $cleanableTableName,
                            $cleanupName
                        ));
                    } else {
                        $io->out(__(
                            '{0} issues fixed in table {1} ({2})',
                            $recordCount,
                            $cleanableTableName,
                            $cleanupName
                        ));
                    }
                }

                $timeEnd = microtime(true);
                $timeExecuted = round($timeEnd - $timeStart, 2);
                $io->verbose("Cleanup up end ({$timeExecuted}s): $cleanableTableName:$cleanupMethodName");
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

    /**
     * Runs series of checks to make sure database is in valid state to run the cleanup command.
     *
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException If database is not in valid state.
     */
    private function assertDatabaseState(): void
    {
        // Check 1. Users table exist in db
        /** @var \Cake\Database\Connection $connection */
        $connection = ConnectionManager::get('default');
        $listTables = $connection->getSchemaCollection()->listTables();
        if (!in_array('users', $listTables)) {
            throw new InternalErrorException(
                __('Cleanup command cannot be executed on an instance having no users table.')
            );
        }

        // Check 2. Atleast one active administrator is present
        $admin = $this->Users->findFirstAdmin();
        if (is_null($admin)) {
            throw new InternalErrorException(
                __('Cleanup command cannot be executed on an instance having no active administrator.')
            );
        }
    }

    /**
     * Convert the method name to a human readeable string. eg. "cleanupMethodName" become "Method Name".
     *
     * @param string $methodName Method name
     * @return string
     */
    private function methodNameToCleanupName(string $methodName): string
    {
        // Remove the "cleanup" prefix if present
        $name = preg_replace('/^cleanup/i', '', $methodName);
        if ($name === '' || $name === null) {
            $name = $methodName;
        }
        // Add a space before each uppercase letter except the first one
        $name = preg_replace('/(?<!^)([A-Z])/', ' $1', $name);

        return strtolower($name);
    }
}
