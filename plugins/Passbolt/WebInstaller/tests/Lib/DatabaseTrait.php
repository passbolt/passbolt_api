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
namespace Passbolt\WebInstaller\Test\Lib;

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Passbolt\WebInstaller\Utility\DatabaseConfiguration;

trait DatabaseTrait
{

    /**
     * Truncate the database tables.
     */
    public function truncateTables()
    {
        $connectionName = 'webinstaller_truncate';
        $config = DatabaseConfiguration::buildConfig(Configure::read('Testing.Datasources.test'));
        ConnectionManager::setConfig($connectionName, $config);
        $connection = ConnectionManager::get($connectionName);
        $tables = $connection->execute('SHOW TABLES')->fetchAll();
        foreach ($tables as $table) {
            $connection->query('DROP TABLE ' . $table[0]);
        }
        ConnectionManager::drop($connectionName);
    }
}
