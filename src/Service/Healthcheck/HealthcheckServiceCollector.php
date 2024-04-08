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

namespace App\Service\Healthcheck;

use Cake\Utility\Inflector;

class HealthcheckServiceCollector
{
    /**
     * @var \App\Service\Healthcheck\HealthcheckServiceInterface[]
     */
    protected array $services = [];
    /**
     * @var string[]
     */
    protected array $domainTitles = [];

    /**
     * List of all available health check domains.
     */
    public const DOMAIN_ENVIRONMENT = 'environment';
    public const DOMAIN_CONFIG_FILES = 'configFiles';
    public const DOMAIN_CORE = 'core';
    public const DOMAIN_APPLICATION = 'application';
    public const DOMAIN_SSL = 'ssl';
    public const DOMAIN_SMTP_SETTINGS = 'smtpSettings';
    public const DOMAIN_DATABASE = 'database';
    public const DOMAIN_GPG = 'gpg';
    public const DOMAIN_JWT = 'jwt';

    /**
     * List of all available levels for health check results.
     */
    public const LEVEL_ERROR = 'error';
    public const LEVEL_WARNING = 'warning';
    public const LEVEL_NOTICE = 'notice';

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
     * @param string $serviceClassName class FQN to retrieve
     * @return \App\Service\Healthcheck\HealthcheckServiceInterface|null
     */
    public function getService(string $serviceClassName): ?HealthcheckServiceInterface
    {
        foreach ($this->getServices() as $healthcheckService) {
            if ($healthcheckService instanceof $serviceClassName) {
                return $healthcheckService;
            }
        }

        return null;
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

    /**
     * Convenient method to retrieve services filtered by domains and service names
     *
     * @param array $domainsIncluded retrieve the services of the given domain
     * @param array $servicesIncluded retrieve the services of the given
     * @return array
     */
    public function getServicesFiltered(array $domainsIncluded, array $servicesIncluded): array
    {
        $services = [];
        foreach ($this->getServices() as $healthcheckService) {
            if (in_array($healthcheckService->domain(), $domainsIncluded)) {
                $services[] = $healthcheckService;
                continue;
            }
            foreach ($servicesIncluded as $serviceIncluded) {
                if (get_class($healthcheckService) === $serviceIncluded) {
                    $services[] = $healthcheckService;
                }
            }
        }

        return $services;
    }

    /**
     * Returns title to show it to the user for this domain health check.
     *
     * @param string $domain Domain to get title from.
     * @return string
     */
    public function getTitleFromDomain(string $domain): string
    {
        $domainTitleMapping = $this->getDomainTitleMapping();

        if (isset($domainTitleMapping[$domain])) {
            return $domainTitleMapping[$domain];
        }

        // If mapping not found, change it to humanize form programmatically
        return Inflector::humanize($domain);
    }

    /**
     * @return array
     */
    protected function getDomainTitleMapping(): array
    {
        return [
            self::DOMAIN_ENVIRONMENT => __('Environment'),
            self::DOMAIN_CONFIG_FILES => __('Config files'),
            self::DOMAIN_CORE => __('Core config'),
            self::DOMAIN_SMTP_SETTINGS => __('SMTP settings'),
            self::DOMAIN_APPLICATION => __('Application configuration'),
            self::DOMAIN_DATABASE => __('Database'),
            self::DOMAIN_GPG => __('GPG Configuration'),
            self::DOMAIN_JWT => __('JWT Authentication'),
            self::DOMAIN_SSL => __('SSL Certificate'),
        ];
    }

    /**
     * @return array
     */
    public function getDomainsInCollectedServices(): array
    {
        $domains = [];
        foreach ($this->services as $service) {
            if (!in_array($service->domain(), $domains)) {
                $domains[] = $service->domain();
            }
        }

        return $domains;
    }
}
