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
 * @since         5.9.0
 */
namespace App\Test\TestCase\Service\Healthcheck\Database;

use App\Service\Healthcheck\Database\MariadbMysqlVersionDeprecateHealthcheck;
use Cake\Database\Connection;
use Cake\Database\Driver\Mysql;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Service\Healthcheck\Database\MariadbMysqlVersionDeprecateHealthcheck
 */
class MariadbMysqlVersionDeprecateHealthcheckTest extends TestCase
{
    private ?MariadbMysqlVersionDeprecateHealthcheck $healthcheck;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->healthcheck = new MariadbMysqlVersionDeprecateHealthcheck();
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        $this->cleanupConnectionManagerMock();
        unset($this->healthcheck);

        parent::tearDown();
    }

    public static function supportedDriverMockDetailsProvider(): array
    {
        return [
            [true, '10.6.24-MariaDB-ubu2204-log'],
            [true, '10.11.14-MariaDB-ubu2204-log'],
            [false, '8.0.44-0+deb11u1'],
        ];
    }

    /**
     * @dataProvider supportedDriverMockDetailsProvider
     * @param bool $isMariaDb Value for isMariaDb() method to mock.
     * * @param string $version Version number to mock.
     * @return void
     */
    public function testMariadbMysqlVersionDeprecateHealthcheck_Pass(bool $isMariaDb, string $version): void
    {
        $this->mockDriverVersionConnection($isMariaDb, $version);

        $result = $this->healthcheck->check()->isPassed();
        $this->assertTrue($result);
    }

    public static function invalidDriverMockDetailsProvider(): array
    {
        return [
            [true, '10.3.38-MariaDB-0ubuntu0.20.04.1'],
            [false, '5.7.44-0+deb11u1'],
        ];
    }

    /**
     * @dataProvider invalidDriverMockDetailsProvider
     * @param bool $isMariaDb Value for isMariaDb() method to mock.
     * @param string $version Version number to mock.
     * @return void
     */
    public function testMariadbMysqlVersionDeprecateHealthcheck_Fail_MariadbOlderVersion(bool $isMariaDb, string $version): void
    {
        $this->mockDriverVersionConnection($isMariaDb, $version);

        $result = $this->healthcheck->check()->isPassed();
        $this->assertFalse($result);
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    /**
     * Mock database connection.
     *
     * @return void
     */
    private function mockDriverVersionConnection(bool $isMariaDb, string $version): void
    {
        $stub = $this->getMockBuilder(Mysql::class)
            ->onlyMethods(['connect', 'isMariaDb', 'version'])
            ->getMock();
        $stub->method('isMariaDb')->willReturn($isMariaDb);
        $stub->method('version')->willReturn($version);

        ConnectionManager::setConfig('mock_conn', [
            'className' => Connection::class,
            'driver' => $stub,
        ]);
        ConnectionManager::alias('mock_conn', 'default');
    }

    /**
     * Clean up: Drop connection created for testing and reinstate default alias to 'test'.
     *
     * @see https://book.cakephp.org/5/en/development/testing.html#test-connections
     * @return void
     */
    private function cleanupConnectionManagerMock(): void
    {
        ConnectionManager::alias('test', 'default');
        ConnectionManager::drop('mock_conn');
    }
}
