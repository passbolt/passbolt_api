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
 * @since         2.13.0
 */
namespace App\Utility\Healthchecks;

abstract class AbstractHealthcheckService
{
    /**
     * @var string $serviceName
     */
    protected $serviceName;

    /**
     * @var string $serviceCategory
     */
    protected $serviceCategory;

    /**
    /**
     *
     * @var array of Healthchecks
     */
    protected $checks;

    /**
     * Health check service constructor
     *
     * @param string $serviceName name
     * @param string|null $serviceCategory serviceCategory
     */
    public function __construct(string $serviceName, ?string $serviceCategory = null)
    {
        $this->serviceName = $serviceName;
        if ($serviceCategory !== null) {
            $this->serviceCategory = $serviceCategory;
        }
        $this->checks = [];
    }

    /**
     * @param string $checkName health check name
     * @param bool $success optional
     * @return \App\Utility\Healthchecks\Healthcheck
     */
    protected function healthcheckFactory(string $checkName, ?bool $success = null): Healthcheck
    {
        return new Healthcheck($checkName, $this->serviceName, $success);
    }

    /**
     * @return array of Healthchecks
     */
    abstract public function check(): array;

    // =======================================================
    // GETTERS
    // =======================================================

    /**
     * @return array[]
     */
    protected function getHealthchecks(): array
    {
        if ($this->serviceCategory !== null) {
            return [$this->serviceCategory => $this->checks];
        }

        return $this->checks;
    }

    /**
     * @return string name
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * @return string serviceCategory
     */
    public function getServiceCategory(): string
    {
        return $this->serviceCategory;
    }

    // =======================================================
    // SETTERS
    // =======================================================

    /**
     * @param string $serviceName name
     * @return static
     */
    public function setServiceName(string $serviceName): AbstractHealthcheckService
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    /**
     * @param string $serviceCategory serviceCategory
     * @return static
     */
    public function setCategory(string $serviceCategory): AbstractHealthcheckService
    {
        $this->serviceCategory = $serviceCategory;

        return $this;
    }
}
