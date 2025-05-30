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
 * @since         3.1.0
 */
namespace App\Test\TestCase\Command;

use App\Command\SqlExportCommand;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Database\Driver\Mysql;
use Cake\Database\Driver\Postgres;
use Cake\Database\Exception\MissingDriverException;
use Cake\Datasource\ConnectionManager;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\WebInstaller\Utility\DatabaseConfiguration;

/**
 * @covers \App\Command\SqlExportCommand
 */
class SqlExportCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use PassboltCommandTestTrait;
    use TruncateDirtyTables;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        ConnectionManager::drop('dummy_connection');

        parent::tearDown();
    }

    public function testEmptyCacheDatabaseFileExists()
    {
        $this->assertTrue(file_exists(SqlExportCommand::CACHE_DATABASE_DIRECTORY . 'empty'));
    }

    /**
     * Basic help test
     */
    public function testSqlExportCommand_Help()
    {
        $this->exec('passbolt sql_export -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Utility to export SQL database backups.');
        $this->assertOutputContains('cake passbolt sql_export');
    }

    public function testSqlExportCommand_OnNonSupportedDriver()
    {
        // Create a dummy connection with non-supported driver
        $dummyConnectionName = 'dummy_connection';
        $config = ConnectionManager::getConfig('default');
        $config['driver'] = 'SomeUnsupportedDriver';
        ConnectionManager::setConfig($dummyConnectionName, $config);

        $this->expectException(MissingDriverException::class);
        $this->exec('passbolt sql_export -d ' . $dummyConnectionName);
    }

    public function testSqlExportCommand_Story()
    {
        $testDir = SqlExportCommand::CACHE_DATABASE_DIRECTORY;
        $this->emptyDirectory($testDir);

        $testFile = 'foo';

        // The file gets created
        $this->exec("passbolt sql_export --dir {$testDir} --file {$testFile} --force");
        $this->assertExitSuccess();

        // Without force, exit an error.
        $this->exec("passbolt sql_export --dir {$testDir} --file {$testFile}");
        $this->assertExitError();

        // With force, exit success
        $this->exec("passbolt sql_export --dir {$testDir} --file {$testFile} --force");
        $this->assertExitSuccess();

        // Kind of validate the dump
        $dumpContent = file_get_contents($testDir . DS . $testFile);
        $tables = (new DatabaseConfiguration())->getTables();
        foreach ($tables as $table) {
            if (ConnectionManager::get('default')->getDriver() instanceof Mysql) {
                $this->assertStringContainsString("DROP TABLE IF EXISTS `$table`;", $dumpContent);
                $this->assertStringContainsString("CREATE TABLE `$table`", $dumpContent);
            }
            if (ConnectionManager::get('default')->getDriver() instanceof Postgres) {
                $this->assertMatchesRegularExpression("/CREATE TABLE .*\.$table/", $dumpContent);
            }
        }

        // With clear previous dump(s)
        $this->exec("passbolt sql_export --dir {$testDir} --clear-previous");
        $this->assertExitSuccess();

        // The previous dump has been deleted.
        $this->assertFalse(file_exists($testDir . DS . $testFile));
    }

    public function testMysqlExportCommandHelp()
    {
        $this->exec('passbolt mysql_export -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Utility to export SQL database backups.');
        $this->assertOutputContains('cake passbolt mysql_export');
    }

    /**
     * @Given A driver is not supported
     * @When I run "passbolt mysql_export"
     * @Then the command cannot be run.
     */
    public function testMysqlExportCommandOnNonSupportedDriver()
    {
        // Create a dummy connection with non-supported driver
        $dummyConnectionName = 'dummy_connection';
        $config = ConnectionManager::getConfig('default');
        $config['driver'] = 'SomeUnsupportedDriver';
        ConnectionManager::setConfig($dummyConnectionName, $config);

        $this->expectException(MissingDriverException::class);
        $this->exec('passbolt mysql_export -d ' . $dummyConnectionName);
    }

    /**
     * Various scenarios when specifying a file name or not.
     * This test covers IMO all the cases where $dir is specified.
     *
     * @group mysqldump
     */
    public function testMysqlExportCommandStory()
    {
        $testDir = SqlExportCommand::CACHE_DATABASE_DIRECTORY;
        $this->emptyDirectory($testDir);

        $testFile = 'foo';

        // The file gets created
        $this->exec("passbolt mysql_export --dir {$testDir} --file {$testFile} --force");
        $this->assertExitSuccess();

        // Without force, exit an error.
        $this->exec("passbolt mysql_export --dir {$testDir} --file {$testFile}");
        $this->assertExitError();

        // With force, exit success
        $this->exec("passbolt mysql_export --dir {$testDir} --file {$testFile} --force");
        $this->assertExitSuccess();

        // Kind of validate the dump
        $dumpContent = file_get_contents($testDir . DS . $testFile);
        $tables = (new DatabaseConfiguration())->getTables();
        foreach ($tables as $table) {
            if (ConnectionManager::get('default')->getDriver() instanceof Mysql) {
                $this->assertStringContainsString("DROP TABLE IF EXISTS `$table`;", $dumpContent);
                $this->assertStringContainsString("CREATE TABLE `$table`", $dumpContent);
            }
            if (ConnectionManager::get('default')->getDriver() instanceof Postgres) {
                $this->assertMatchesRegularExpression("/CREATE TABLE .*\.$table/", $dumpContent);
            }
        }

        // With clear previous dump(s)
        $this->exec("passbolt mysql_export --dir {$testDir} --clear-previous");
        $this->assertExitSuccess();

        // The previous dump has been deleted.
        $this->assertFalse(file_exists($testDir . DS . $testFile));
    }
}
