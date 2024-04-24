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

use Cake\Core\Exception\CakeException;
use Cake\Database\Connection;
use Cake\Database\Driver\Mysql;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

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
            'driver' => $data['driver'] ?? env('DATASOURCES_DEFAULT_DRIVER', Mysql::class),
            'persistent' => false,
            'host' => $data['host'],
            'port' => $data['port'],
            'username' => $data['username'],
            'password' => $data['password'],
            'database' => $data['database'],
            'schema' => $data['schema'] ?? null,
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
        TableRegistry::getTableLocator()->clear();
    }

    /**
     * Test database connection.
     *
     * @throws \Cake\Core\Exception\CakeException when a connection cannot be established
     * @return bool
     */
    public static function testConnection(): bool
    {
        $connection = ConnectionManager::get('default');
        if (!($connection instanceof Connection)) {
            return false;
        }

        try {
            $connection->getDriver()->connect();

            return true;
        } catch (\Throwable $e) {
            Log::error($e->getMessage());

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
     * @throws \Cake\Core\Exception\CakeException If the database schema does not validate
     * @return void
     */
    public static function validateSchema()
    {
        $tables = self::getTables();
        $expectedTables = self::getSchemaTables(1);
        foreach ($expectedTables as $expectedTable) {
            if (!in_array($expectedTable, $tables)) {
                throw new CakeException(__('The database schema does not match the one expected'));
            }
        }
    }

    /**
     * Get schema tables list. (per version number).
     *
     * @param int $version passbolt major version number.
     * @return array
     */
    public static function getSchemaTables(int $version = 2): array
    {
        // List of tables for passbolt v1.
        $tables = [
            'authentication_tokens',
            'avatars',
            'comments',
            'email_queue',
            'favorites',
            'gpgkeys',
            'groups',
            'groups_users',
            'permissions',
            'profiles',
            'resources',
            'roles',
            'secrets',
            'users',
        ];

        // Extra tables for passbolt v2.
        if ($version == 2) {
            $tables = array_merge($tables, [
                //'burzum_file_storage_phinxlog', // dropped in v2.8
                //'email_queue_phinxlog',
                'phinxlog',
            ]);
        }

        return $tables;
    }
}
