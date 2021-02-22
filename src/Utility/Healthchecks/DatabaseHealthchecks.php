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
 * @since         2.1.2
 */
namespace App\Utility\Healthchecks;

use Cake\Database\Exception as DatabaseException;
use Cake\Database\Exception\MissingConnectionException;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

class DatabaseHealthchecks
{
    /**
     * Run all databases health checks
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function all(?array $checks = []): array
    {
        // init results to false by default
        $checks = self::canConnect($checks);
        $checks = self::supportedBackend($checks);
        $checks = self::tableCount($checks);
        $checks = self::defaultContent($checks);

        return $checks;
    }

    /**
     * Check if application can connect to database
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function canConnect(?array $checks = []): array
    {
        $checks['database']['connect'] = false;
        try {
            $connection = ConnectionManager::get('default');
            $connection->connect();
            $checks['database']['connect'] = true;
        } catch (MissingConnectionException $connectionError) {
            $errorMsg = $connectionError->getMessage();
            if (method_exists($connectionError, 'getAttributes')) {
                $attributes = $connectionError->getAttributes();
                if (isset($errorMsg['message'])) {
                    $checks['database']['info'] .= ' ' . $attributes['message'];
                }
            }
        }

        return $checks;
    }

    /**
     * Is the database engine supported
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function supportedBackend(?array $checks = []): array
    {
        $checks['database']['supportedBackend'] = false;
        $connection = ConnectionManager::get('default');
        $config = $connection->config();
        if ($config['driver'] === 'Cake\Database\Driver\Mysql') {
            $checks['database']['supportedBackend'] = true;
        }

        return $checks;
    }

    /**
     * Check if tables are present
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function tableCount(?array $checks = []): array
    {
        $checks['database']['info']['tablesCount'] = 0;
        $checks['database']['tablesCount'] = false;
        try {
            $connection = ConnectionManager::get('default');
            $tables = $connection->execute('show tables')->fetchAll('assoc');

            if (isset($tables) && count($tables)) {
                $checks['database']['tablesCount'] = (count($tables) > 0);
                $checks['database']['info']['tablesCount'] = count($tables);
            }
        } catch (DatabaseException $connectionError) {
        }

        return $checks;
    }

    /**
     * Check if some default data is present
     * We only check the number of roles
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public static function defaultContent(?array $checks = []): array
    {
        $checks['database']['defaultContent'] = false;
        try {
            $Roles = TableRegistry::getTableLocator()->get('Roles');
            if (!empty($Roles)) {
                $i = $Roles->find('all')->count();
                $checks['database']['defaultContent'] = ($i > 3);
            }
        } catch (DatabaseException $e) {
        }

        return $checks;
    }
}
