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
 * @since         2.5.0
 */
namespace Passbolt\WebInstaller\Utility;

use App\Utility\Healthchecks;
use Cake\Core\Exception\Exception;
use Cake\Database\Connection;
use Cake\Database\Driver\Mysql;
use Cake\Datasource\ConnectionManager;

class DatabaseConfiguration
{
    /**
     * Build a database configuration
     *
     * @param string[] $data form data
     * @return array
     */
    public static function buildConfig(array $data): array
    {
        return [
            'className' => 'Cake\Database\Connection',
            // For the moment, we take MySQL per default.
            // Later we will offer the possibility to choose between MySQL and Postgres in the form
            'driver' => $data['driver'] ?? env('DATASOURCES_DEFAULT_DRIVER', Mysql::class),
            'persistent' => false,
            'host' => $data['host'],
            'port' => $data['port'],
            'username' => $data['username'],
            'password' => $data['password'],
            'database' => $data['database'],
            'encoding' => 'utf8',
            'timezone' => 'UTC',
        ];
    }

    /**
     * Set the default database config
     *
     * @param array $config The config to set
     * @return void
     */
    public static function setDefaultConfig($config)
    {
        // usefull in tests where 'default' name is mapped to config 'test'
        // here we need the original config name after aliasing
        // so that we drop/rebuild the test config and not the default one
        $configName = ConnectionManager::get('default')->configName();
        ConnectionManager::drop($configName);
        $dbConfig = self::buildConfig($config);
        ConnectionManager::setConfig($configName, $dbConfig);
    }

    /**
     * Test database connection.
     *
     * @throws \Cake\Core\Exception\Exception when a connection cannot be established
     * @return bool
     */
    public static function testConnection()
    {
        $connection = ConnectionManager::get('default');
        if (!($connection instanceof Connection)) {
            return false;
        }

        try {
            $connection->connect();

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Get the database tables names
     *
     * @return array|\ArrayAccess
     */
    public static function getTables()
    {
        return ConnectionManager::get('default')->getSchemaCollection()->listTables();
    }

    /**
     * Validate the database schema.
     *
     * @throws \Cake\Core\Exception\Exception If the database schema does not validate
     * @return void
     */
    public static function validateSchema()
    {
        $tables = self::getTables();
        $expectedTables = Healthchecks::getSchemaTables(1);
        foreach ($expectedTables as $expectedTable) {
            if (!in_array($expectedTable, $tables)) {
                throw new Exception(__('The database schema does not match the one expected'));
            }
        }
    }
}
