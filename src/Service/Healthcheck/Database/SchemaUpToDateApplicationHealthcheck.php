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

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Datasource\ConnectionManager;
use Migrations\Migrations;

class SchemaUpToDateApplicationHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        try {
            $this->status = !$this->needMigration();
        } catch (\Exception $e) {
            // Do nothing
        }

        return $this;
    }

    /**
     * Check if the app or plugins need a database migration
     *
     * @return bool
     */
    public function needMigration(): bool
    {
        $Migrations = new Migrations(['connection' => ConnectionManager::get('default')->configName()]);
        $migrations = $Migrations->status();
        foreach ($migrations as $migration) {
            if ($migration['status'] === 'down') {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_APPLICATION;
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
        return HealthcheckServiceCollector::LEVEL_ERROR;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The database schema is up to date.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The database schema is not up to date.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return [
            __('Run the migration scripts:'),
            'sudo su -s /bin/bash -c "' . ROOT . DS . 'bin/cake migrations migrate --no-lock" ' . PROCESS_USER,
            __('See https://www.passbolt.com/help/tech/update'),
        ];
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_APPLICATION;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'schema';
    }
}
