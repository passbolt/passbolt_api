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
 * @since         5.13.0
 */
namespace App\Test\TestCase\Service\Healthcheck\Database;

use App\Service\Healthcheck\Database\ConnectDatabaseHealthcheck;
use App\Test\Lib\Database\Driver\CustomDatabaseDriverStub;
use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Service\Healthcheck\Database\ConnectDatabaseHealthcheck
 */
class ConnectDatabaseHealthcheckTest extends TestCase
{
    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        // cleanup the custom connection settings injected over the tests
        ConnectionManager::alias('test', 'default');
        ConnectionManager::drop('mock_conn');

        parent::tearDown();
    }

    public function testConnectDatabaseHealthcheckTest_HelpMessage(): void
    {
        $dbHealthcheck = new ConnectDatabaseHealthcheck();

        // The help message must list the permitted DB drivers.
        $supportedDriversLine = $dbHealthcheck->getHelpMessage()[0];
        $this->assertStringContainsString('Cake\Database\Driver\Mysql', $supportedDriversLine);
        $this->assertStringContainsString('Cake\Database\Driver\Postgres', $supportedDriversLine);
    }

    public function testConnectDatabaseHealthcheckTest_HelpMessageContainsRegisteredCustomDriver(): void
    {
        $dbHealthcheck = new ConnectDatabaseHealthcheck([CustomDatabaseDriverStub::class]);

        // The help message must list the registered custom driver alongside the default ones.
        $supportedDriversLine = $dbHealthcheck->getHelpMessage()[0];
        $this->assertStringContainsString('CustomDatabaseDriverStub', $supportedDriversLine);
    }

    public function testConnectDatabaseHealthcheckTest_Success_WithDefaultSupportedDriver(): void
    {
        $dbHealthcheck = new ConnectDatabaseHealthcheck();
        // The default test connection uses one of the officially supported drivers
        // (Mysql/MariaDB or Postgres), so the check must pass out of the box.
        $this->assertTrue($dbHealthcheck->isDriverSupported());
        $this->assertTrue($dbHealthcheck->check()->isPassed());
    }

    public function testConnectDatabaseHealthcheckTest_Fail_WithUnsupportedDriver(): void
    {
        // create a connection with a stub driver that is not previously added to the list of supported drivers fails
        ConnectionManager::setConfig('mock_conn', [
            'className' => Connection::class,
            'driver' => CustomDatabaseDriverStub::class,
        ]);
        ConnectionManager::alias('mock_conn', 'default');

        $dbHealthcheck = new ConnectDatabaseHealthcheck();

        $this->assertFalse($dbHealthcheck->isDriverSupported());
        $this->assertFalse($dbHealthcheck->check()->isPassed());
        $this->assertSame(
            'The driver defined in the database configuration is not supported.',
            $dbHealthcheck->getFailureMessage(),
        );
    }

    public function testConnectDatabaseHealthcheckTest_ReturnsTrue_WhenCustomDriverRegistered(): void
    {
        ConnectionManager::setConfig('mock_conn', [
            'className' => Connection::class,
            'driver' => CustomDatabaseDriverStub::class,
        ]);
        ConnectionManager::alias('mock_conn', 'default');

        // registering the driver on the check module instantiation as done with DI makes it a supported driver
        $dbHealthcheck = new ConnectDatabaseHealthcheck([CustomDatabaseDriverStub::class]);
        $this->assertTrue($dbHealthcheck->isDriverSupported());
    }

    public function testConnectDatabaseHealthcheckTest_KeepsDefaultDriversWhenCustomRegistered(): void
    {
        $dbHealthcheck = new ConnectDatabaseHealthcheck([CustomDatabaseDriverStub::class]);

        // Registering a custom driver must not drop the officially supported ones.
        // Mysql or Postgres being the only default drivers for the unit-tests run, a simple check is ok
        $this->assertTrue($dbHealthcheck->isDriverSupported());
    }
}
