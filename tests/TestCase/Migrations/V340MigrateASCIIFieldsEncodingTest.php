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

use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\TestCase;

class V340MigrateASCIIFieldsEncodingTest extends TestCase
{
    /**
     * Check that fields susceptible to be UUID or basic strings are well encoded in ASCII
     */
    public function testV340MigrateASCIIFieldsEncoding()
    {
        $schemaCollection = ConnectionManager::get('test')->getSchemaCollection();
        $tables = $schemaCollection->listTables();

        $errors = [];
        foreach ($tables as $table) {
            $columns = $schemaCollection->describe($table, ['forceRefresh' => true])->columns();
            foreach ($columns as $columnName) {
                $col = ConnectionManager::get('test')
                    ->execute("SHOW FULL COLUMNS FROM $table WHERE Field = '$columnName';")
                    ->fetch('assoc');

                $colType = $col['Type'];
                $collation = $col['Collation'];
                // Skip if it is an integer
                if (substr($colType, 0, 3) === 'int') {
                    continue;
                }
                $isSuspiciousType = in_array($colType, $this->getSuspiciousColumnTypes());
                $isSuspiciousName = in_array($columnName, $this->getSuspiciousColumnNames());
                $isId = $columnName === 'id' || strpos($columnName, '_id') !== false;

                if ($isId || $isSuspiciousType || $isSuspiciousName) {
                    if ($collation != 'ascii_general_ci') {
                        $errors[] = "Column '$columnName' in table '$table' is no ascii_general_ci but '$collation'";
                        debug($colType);
                    }
                }
            }
        }

        $this->assertSame([], $errors);
    }

    /**
     * Column names that are probably UUIDs
     *
     * @return string[]
     */
    public function getSuspiciousColumnNames(): array
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

    public function getSuspiciousColumnTypes(): array
    {
        return [
            'char(1)',
            'char(36)',
        ];
    }
}
