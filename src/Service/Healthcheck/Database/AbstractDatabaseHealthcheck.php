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
use App\Service\Healthcheck\HealthcheckWithOptionsInterface;

abstract class AbstractDatabaseHealthcheck implements HealthcheckServiceInterface, HealthcheckWithOptionsInterface, HealthcheckCliInterface // phpcs:ignore
{
    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    protected bool $status = false;

    /**
     * @var array
     */
    private array $additionalOptions = [];

    /**
     * @return string
     */
    protected function getDatasource(): string
    {
        return $this->getOptions()['datasource'] ?? 'default';
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
        return HealthcheckServiceCollector::LEVEL_ERROR;
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
    public function setOptions(array $options): void
    {
        $this->additionalOptions = $options;
    }

    /**
     * @inheritDoc
     */
    public function getOptions(): array
    {
        return $this->additionalOptions;
    }
}
