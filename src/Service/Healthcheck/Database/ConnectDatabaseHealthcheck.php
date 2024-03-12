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
 * @since         4.6.0
 */

namespace App\Service\Healthcheck\Database;

use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Database\Exception\MissingConnectionException;
use Cake\Datasource\ConnectionManager;

class ConnectDatabaseHealthcheck extends AbstractDatabaseHealthcheck
{
    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $datasource = $this->getDatasource();
        try {
            /** @var \Cake\Database\Connection $connection */
            $connection = ConnectionManager::get($datasource);
            $connection->getDriver()->connect();
            $this->status = true;
        } catch (MissingConnectionException $connectionError) {
            // Do nothing
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The application is able to connect to the database');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The application is not able to connect to the database.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return [
            __(
                'Double check the host, database name, username and password in {0}.',
                CONFIG . 'passbolt.php'
            ),
            __('Make sure the database exists and is accessible for the given database user.'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'connect';
    }

    /**
     * This method is only here because we don't have "datasource" access outside.
     *
     * @deprecated As of v4.7.0, will be removed in v5.
     * @return bool
     */
    public function isSupportedBackend(): bool
    {
        $result = false;

        $connection = ConnectionManager::get($this->getDatasource());
        $config = $connection->config();
        if (in_array($config['driver'], ['Cake\Database\Driver\Mysql', 'Cake\Database\Driver\Postgres'])) {
            $result = true;
        }

        return $result;
    }
}
