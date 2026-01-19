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
 * @since         5.9.0
 */

namespace App\Service\Healthcheck\Database;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Service\Healthcheck\SkipHealthcheckInterface;
use Cake\Database\Driver\Mysql;
use Cake\Database\Exception\MissingConnectionException;
use Cake\Datasource\ConnectionManager;

class MariadbMysqlVersionDeprecateHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface, SkipHealthcheckInterface // phpcs:ignore
{
    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    /**
     * @var bool
     */
    private bool $isSkipped = false;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $connection = ConnectionManager::get('default');
        // Get the driver instance
        $driver = $connection->getDriver();

        // Skip if connection error
        try {
            $driver->connect();
        } catch (MissingConnectionException $_) {
            $this->markAsSkipped();

            return $this;
        }

        if ($driver instanceof Mysql) {
            if ($driver->isMariaDb()) {
                /**
                 * mariadb
                 *
                 * @see https://endoflife.date/mariadb
                 */
                $this->status = version_compare($driver->version(), '10.6', '>=');
            } else {
                /**
                 * mysql
                 *
                 * @see https://endoflife.date/mysql
                 */
                $this->status = version_compare($driver->version(), '8.0', '>=');
            }
        } else {
            $this->status = true;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_DATABASE;
    }

    /**
     * @inheritDoc
     */
    public function isPassed(): bool
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function level(): string
    {
        return HealthcheckServiceCollector::LEVEL_WARNING;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The database version is supported.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The database version is deprecated.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
    {
        return null;
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_DATABASE;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'mariadbMysqlVersionDeprecate';
    }

    /**
     * @inheritDoc
     */
    public function markAsSkipped(): void
    {
        $this->isSkipped = true;
    }

    /**
     * @inheritDoc
     */
    public function isSkipped(): bool
    {
        return $this->isSkipped;
    }
}
