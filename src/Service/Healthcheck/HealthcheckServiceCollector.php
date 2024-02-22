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

namespace App\Service\Healthcheck;

class HealthcheckServiceCollector
{
    /**
     * @var \App\Service\Healthcheck\HealthcheckServiceInterface[]
     */
    private array $services = [];

    /**
     * Add a new service to the collector.
     *
     * @param \App\Service\Healthcheck\HealthcheckServiceInterface $healthcheckService Health check service.
     * @return void
     */
    public function addService(HealthcheckServiceInterface $healthcheckService): void
    {
        $this->services[] = $healthcheckService;
    }

    /**
     * Returns all services available in this collector.
     *
     * @return \App\Service\Healthcheck\HealthcheckServiceInterface[]
     */
    public function getServices(): array
    {
        return $this->services;
    }
}
