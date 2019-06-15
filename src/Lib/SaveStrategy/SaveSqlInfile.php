<?php
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
 * @since         2.7.0
 */
namespace PassboltTestData\Lib\SaveStrategy;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Hash;

class SaveSqlInfile
{
    /**
     * The shell the strategy is executing on.
     *
     * @var Shell
     */
    private $shell;

    /**
     * The database connection
     *
     * @var Object
     */
    private $connection;

    /**
     * Constructor
     *
     * @param Shell $shell The console the strategy is executing by.
     */
    public function __construct(Shell $shell)
    {
        $this->shell = $shell;
        $this->connection = ConnectionManager::get('default');
    }

    /**
     * Save a chunk of entities
     *
     * @param array $data The data to save
     * @return void
     */
    public function save(array $data)
    {
        $table = $this->shell->_Entity->table();
        $columns = $this->getTableColumnNames();
        $fileContent = '';

        foreach ($data as $entity) {
            $row = '';
            foreach ($columns as $column) {
                if (!empty($row)) {
                    $row .= '|';
                }
                if (!isset($entity[$column]) || $entity[$column] === '') {
                    $row .= '\N';
                } else {
                    $row .= $entity[$column];
                }
            }
            $row .= "__ENDOFLINE__";
            $fileContent .= $row;
            unset($entity);
        }

        $path = TMP . 'tests' . DS . 'passbolt_test_data' . DS . "passbolt_test_data_$table.sql";
        file_put_contents($path, $fileContent);
        $sql = "LOAD DATA INFILE '$path' INTO TABLE $table FIELDS TERMINATED BY '|' LINES TERMINATED BY '__ENDOFLINE__';";
        $this->connection->execute($sql);
        $sql = "OPTIMIZE TABLE $table;";
        $this->connection->execute($sql);
    }

    /**
     * Get table column names
     *
     * @return array
     */
    private function getTableColumnNames()
    {
        $table = $this->shell->_Entity->table();
        $sql = "SHOW columns FROM $table;";
        $result = $this->connection->execute($sql)->fetchAll();

        return Hash::extract($result, '{n}.0');
    }
}
