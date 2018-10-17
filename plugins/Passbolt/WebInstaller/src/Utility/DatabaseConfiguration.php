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
 * @since         2.5.0
 */
namespace Passbolt\WebInstaller\Utility;

use App\Utility\Healthchecks;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Hash;

class DatabaseConfiguration
{

    /**
     * Build a database configuration
     * @param array $data form data
     * @return array
     */
    public static function buildConfig($data)
    {
        return [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
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
     * @param array $config The config to set
     * @return void
     */
    public static function setDefaultConfig($config)
    {
        $defaultConfigName = self::getDefaultConfigName();
        ConnectionManager::drop($defaultConfigName);
        $dbConfig = self::buildConfig($config);
        ConnectionManager::setConfig($defaultConfigName, $dbConfig);
    }

    /**
     * Test database connection.
     * @throws Exception when a connection cannot be established
     * @return bool
     */
    public static function testConnection()
    {
        $defaultConfigName = self::getDefaultConfigName();
        $connection = ConnectionManager::get($defaultConfigName);

        try {
            $connection->execute('SHOW TABLES')->fetchAll('assoc');
        } catch (\PDOException $e) {
            return false;
        }

        return true;
    }

    /**
     * Get the default configuration name.
     * @return string "test" if the tests are executed, "default" otherwise.
     */
    public static function getDefaultConfigName()
    {
        return defined('TEST_IS_RUNNING') && TEST_IS_RUNNING ? 'test' : 'default';
    }

    /**
     * Get the database tables names
     * @return array
     */
    public static function getTables()
    {
        $defaultConfigName = self::getDefaultConfigName();
        $connection = ConnectionManager::get($defaultConfigName);
        $tables = $connection->execute('SHOW TABLES')->fetchAll();

        return Hash::extract($tables, '{n}.0');
    }

    /**
     * Validate the database schema.
     * @throws Exception If the database schema does not validate
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
