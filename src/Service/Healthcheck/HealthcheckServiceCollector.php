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

use Cake\Utility\Inflector;

class HealthcheckServiceCollector
{
    /**
     * @var \App\Service\Healthcheck\HealthcheckServiceInterface[]
     */
    private array $services = [];

    /**
     * List of all available health check domains.
     */
    public const DOMAIN_APPLICATION = 'application';
    public const DOMAIN_SSL = 'ssl';
    public const DOMAIN_SMTP_SETTINGS = 'smtpSettings';
    public const DOMAIN_DATABASE = 'database';
    public const DOMAIN_GPG = 'gpg';

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
     * Returns all services available in this collector.
     *
     * @return \App\Service\Healthcheck\HealthcheckServiceInterface[]
     */
    public function getServices(): array
    {
        return $this->services;
    }

    /**
     * Returns title to show it to the user for this domain health check.
     *
     * @param string $domain Domain to get title from.
     * @return string
     */
    public static function getTitleFromDomain(string $domain): string
    {
        $domainTitleMapping = [
            'environment' => __('Environment'),
            'configFiles' => __('Config files'),
            'core' => __('Core config'),
            self::DOMAIN_SMTP_SETTINGS => __('SMTP settings'),
            self::DOMAIN_APPLICATION => __('Application configuration'),
            self::DOMAIN_DATABASE => __('Database'),
            self::DOMAIN_GPG => __('GPG Configuration'),
        ];

        if (isset($domainTitleMapping[$domain])) {
            return $domainTitleMapping[$domain];
        }

        // If mapping not found, change it to humanize form programmatically
        return Inflector::humanize($domain);
    }
}
