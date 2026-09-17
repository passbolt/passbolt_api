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
 * @since         4.7.0
 */

namespace App\Service\Healthcheck\Database;

use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Database\Exception\MissingConnectionException;
use Cake\Datasource\ConnectionManager;

class ConnectDatabaseHealthcheck extends AbstractDatabaseHealthcheck
{
    protected bool $isDriverSupported = false;

    public const DEFAULT_SUPPORTED_DRIVERS = [
        'Cake\Database\Driver\Mysql',
        'Cake\Database\Driver\Postgres',
    ];

    /**
     * @var array
     */
    private array $supportedDrivers;

    /**
     * Only the drivers defined in DEFAULT_SUPPORTED_DRIVERS are officially supported by Passbolt;
     * any additional driver is added at your own risk.
     *
     * @param array|null $additionalSupportedDrivers Custom database drivers to support on top of the default ones.
     */
    public function __construct(?array $additionalSupportedDrivers = null)
    {
        $this->supportedDrivers = array_merge(self::DEFAULT_SUPPORTED_DRIVERS, $additionalSupportedDrivers ?? []);
    }

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $datasource = $this->getDatasource();
        try {
            /** @var \Cake\Database\Connection $connection */
            $connection = ConnectionManager::get($datasource);
            $this->isDriverSupported = $this->isDriverSupported();
            if (!$this->isDriverSupported) {
                return $this;
            }
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
        if (!$this->isDriverSupported) {
            return __('The driver defined in the database configuration is not supported.');
        }

        return __('The application is not able to connect to the database.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
    {
        return [
            __(
                'Ensure that the driver defined in {0} is one of the following: {1}.',
                CONFIG . 'passbolt.php',
                implode(', ', $this->supportedDrivers),
            ),
            __(
                'Double check the host, database name, username and password in {0}.',
                CONFIG . 'passbolt.php',
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
     * @return bool
     */
    public function isDriverSupported(): bool
    {
        $result = false;

        $connection = ConnectionManager::get($this->getDatasource());
        $config = $connection->config();
        if (in_array($config['driver'], $this->supportedDrivers)) {
            $result = true;
        }

        return $result;
    }
}
