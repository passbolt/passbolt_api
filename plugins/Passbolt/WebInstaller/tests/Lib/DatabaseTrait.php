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
namespace Passbolt\WebInstaller\Test\Lib;

use Cake\Datasource\ConnectionManager;

trait DatabaseTrait
{
    /**
     * Drops all the database tables.
     */
    public static function dropAllTables()
    {
        $connection = ConnectionManager::get('default');
        $tables = $connection->execute('SHOW TABLES')->fetchAll();
        foreach ($tables as $table) {
            $quotedTableName = $connection->getDriver()->quoteIdentifier($table[0]);
            $connection->query("DROP TABLE {$quotedTableName}");
        }
    }
}
