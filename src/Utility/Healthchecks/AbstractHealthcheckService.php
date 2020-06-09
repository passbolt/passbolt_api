<?php
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
     * @var array of Healthchecks
     */
    protected $checks;

    /**
     * Health check service constructor
     *
     * @param string $serviceName name
     * @param string $serviceCategory serviceCategory
     */
    public function __construct(string $serviceName, string $serviceCategory = null)
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
     * @return Healthcheck
     */
    protected function healthcheckFactory(string $checkName, bool $success = null)
    {
        return new Healthcheck($checkName, $this->serviceName, $success);
    }

    /**
     * @return array of Healthchecks
     */
    abstract public function check();

    // =======================================================
    // GETTERS
    // =======================================================

    /**
     * @return array[]
     */
    protected function getHealthchecks()
    {
        if ($this->serviceCategory !== null) {
            return [$this->serviceCategory => $this->checks];
        }

        return $this->checks;
    }

    /**
     * @return string name
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @return string serviceCategory
     */
    public function getServiceCategory()
    {
        return $this->serviceCategory;
    }

    // =======================================================
    // SETTERS
    // =======================================================

    /**
     * @param string $serviceName name
     * @return $this
     */
    public function setServiceName(string $serviceName)
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    /**
     * @param string $serviceCategory serviceCategory
     * @return $this
     */
    public function setCategory(string $serviceCategory)
    {
        $this->serviceCategory = $serviceCategory;

        return $this;
    }
}
