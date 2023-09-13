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
 * @since         4.3.0
 */
namespace Passbolt\WebInstaller\Test\TestCase\Utility;

use Cake\Database\Driver\Mysql;
use Cake\Database\Driver\Postgres;
use Cake\TestSuite\TestCase;
use Passbolt\WebInstaller\Utility\DatabaseConfiguration;

class DatabaseConfigurationTest extends TestCase
{
    public function testDatabaseConfiguration_On_Mysql()
    {
        $data = [
            'driver' => Mysql::class,
            'host' => 'foo',
            'port' => 'foo',
            'username' => 'foo',
            'password' => 'foo',
            'database' => 'foo',
            'schema' => 'bar',
        ];

        $sanitizedData = DatabaseConfiguration::mapData($data);

        $this->assertArrayNotHasKey('schema', $sanitizedData);
    }

    public function testDatabaseConfiguration_On_Postgres()
    {
        $data = [
            'driver' => Postgres::class,
            'host' => 'foo',
            'port' => 'foo',
            'username' => 'foo',
            'password' => 'foo',
            'database' => 'foo',
            'schema' => 'bar-with-dash',
        ];

        $sanitizedData = DatabaseConfiguration::mapData($data);

        $this->assertSame('bar-with-dash', $sanitizedData['schema']);
    }
}
