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
 * @since         3.4.0
 */
namespace App\Test\TestCase\Migrations;

use Cake\Database\Driver\Mysql;
use Cake\Database\Driver\Postgres;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\TestCase;

class V340MigrateASCIIFieldsEncodingTest extends TestCase
{
    /**
     * Check that fields susceptible to be UUID or basic strings are well encoded in ASCII
     */
    public function testV340MigrateASCIIFieldsEncoding_MySQL()
    {
        $connection = ConnectionManager::get('test');
        if (!($connection->getDriver() instanceof Mysql)) {
            $this->expectNotToPerformAssertions();

            return;
        }

        $schemaCollection = $connection->getSchemaCollection();
        $tables = $schemaCollection->listTables();

        $errors = [];
        foreach ($tables as $table) {
            $columns = $schemaCollection->describe($table, ['forceRefresh' => true])->columns();
            foreach ($columns as $columnName) {
                $col = ConnectionManager::get('test')
                    ->execute("SHOW FULL COLUMNS FROM `$table` WHERE Field = '$columnName';")
                    ->fetch('assoc');

                $colType = $col['Type'];
                $collation = $col['Collation'];
                $fullName = $table . '.' . $columnName;
                // Skip if it is an integer
                if (substr($colType, 0, 3) === 'int') {
                    continue;
                }
                if (in_array($fullName, $this->getWhiteList())) {
                    continue;
                }
                $isSuspiciousType = in_array($colType, $this->getSuspiciousColumnTypes());
                $isSuspiciousName = in_array($columnName, $this->getSuspiciousAsciiColumnNames());
                $isId = $columnName === 'id' || strpos($columnName, '_id') !== false;

                if ($isId || $isSuspiciousType || $isSuspiciousName) {
                    if ($collation != 'ascii_general_ci') {
                        $errors[] = "Column '$columnName' in table '$table' is no ascii_general_ci but '$collation'";
                    }
                }
            }
        }

        $this->assertSame([], $errors);
    }

    /**
     * Check that fields susceptible to be UUID are of UUID type
     */
    public function testV340MigrateUuidFields_Postgres()
    {
        $connection = ConnectionManager::get('test');
        if (!($connection->getDriver() instanceof Postgres)) {
            $this->expectNotToPerformAssertions();

            return;
        }
        $schemaCollection = ConnectionManager::get('test')->getSchemaCollection();
        $tables = $schemaCollection->listTables();

        $errors = [];
        foreach ($tables as $table) {
            $columns = $schemaCollection->describe($table, ['forceRefresh' => true])->columns();
            foreach ($columns as $columnName) {
                $col = ConnectionManager::get('test')
                    ->execute("SELECT table_name, column_name, data_type
                                FROM information_schema.columns WHERE
                               table_name = '$table' AND column_name = '$columnName';
                       ")
                    ->fetch('assoc');

                $dataType = $col['data_type'];

                if ($this->isUuid($table, $columnName) && $dataType !== 'uuid') {
                    $errors[] = "Column '$columnName' data type in table '$table' is not UUID but $dataType";
                }
            }
        }

        $this->assertSame([], $errors);
    }

    /**
     * Column names that should probably Be ASCII encoded
     *
     * @return string[]
     */
    public function getSuspiciousAsciiColumnNames(): array
    {
        return [
            'aco',
            'aco_foreign_key',
            'aro',
            'aro_foreign_key',
            'foreign_key',
            'foreign_model',
            'hash',
            'status',
            'token',
            'type',
            'created_by',
            'modified_by',
        ];
    }

    /**
     * Indicate if this id is most probably an uuid
     *
     * @param string $tableName table name
     * @param string $columnName column name
     * @return bool
     */
    public function isUuid(string $tableName, string $columnName): bool
    {
        // Some columns do not follow the general pattern and are not UUIDs
        $exceptions = ['email_queue.id', 'gpgkeys.key_id'];

        if (in_array("$tableName.$columnName", $exceptions)) {
            return false;
        }

        return $columnName === 'id'
            || strpos($columnName, '_id') !== false
            || strpos($columnName, 'foreign_key') !== false
            || strpos($columnName, '_by') !== false;
    }

    /**
     * Column types that should most probably be ascii_general_ci
     *
     * @return string[]
     */
    public function getSuspiciousColumnTypes(): array
    {
        return [
            'char(1)',
            'char(36)',
        ];
    }

    /**
     * Columns that are OK, skip the check there.
     *
     * @return string[]
     */
    public function getWhiteList(): array
    {
        return [
            'gpgkeys.key_id',
            'gpgkeys.type',
        ];
    }
}
