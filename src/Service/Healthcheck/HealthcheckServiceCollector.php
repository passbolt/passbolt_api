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

use Cake\Http\Exception\InternalErrorException;
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
    public const DOMAIN_ENVIRONMENT = 'environment';
    public const DOMAIN_CONFIG_FILE = 'configFiles';
    public const DOMAIN_CORE = 'core';
    public const DOMAIN_APPLICATION = 'application';
    public const DOMAIN_SSL = 'ssl';
    public const DOMAIN_SMTP_SETTINGS = 'smtpSettings';
    public const DOMAIN_DATABASE = 'database';
    public const DOMAIN_GPG = 'gpg';
    public const DOMAIN_JWT = 'jwt';
    public const DOMAIN_WEB_INSTALLER = 'web_installer';

    /**
     * List of all available levels for health check results.
     */
    public const LEVEL_ERROR = 'error';
    public const LEVEL_WARNING = 'warning';
    public const LEVEL_NOTICE = 'notice';

    /**
     * Configuration constants.
     */
    // The minimum PHP version soon required. Healthcheck will warn if not satisfied yet.
    public const PHP_NEXT_MIN_VERSION_CONFIG = 'php.nextMinVersion';
    // The minimum PHP version required. Healthcheck will fail if not satisfied yet.
    public const PHP_MIN_VERSION_CONFIG = 'php.minVersion';

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
            self::DOMAIN_ENVIRONMENT => __('Environment'),
            self::DOMAIN_CONFIG_FILE => __('Config files'),
            self::DOMAIN_CORE => __('Core config'),
            self::DOMAIN_SMTP_SETTINGS => __('SMTP settings'),
            self::DOMAIN_APPLICATION => __('Application configuration'),
            self::DOMAIN_DATABASE => __('Database'),
            self::DOMAIN_GPG => __('GPG Configuration'),
            self::DOMAIN_JWT => __('JWT Authentication'),
            self::DOMAIN_SSL => __('SSL'),
        ];

        if (isset($domainTitleMapping[$domain])) {
            return $domainTitleMapping[$domain];
        }

        // If mapping not found, change it to humanize form programmatically
        return Inflector::humanize($domain);
    }

    /**
     * Returns array key of the given domain.
     *
     * @param string $domain Domain to get title from.
     * @return string
     * @throws \Cake\Http\Exception\InternalErrorException When domain-legacy key mapping not found.
     */
    public static function getLegacyDomainKey(string $domain): string
    {
         $domainLegacyKeyMapping = [
            self::DOMAIN_ENVIRONMENT => 'environment',
            self::DOMAIN_CONFIG_FILE => 'configFile',
            self::DOMAIN_CORE => 'core',
            self::DOMAIN_SMTP_SETTINGS => 'smtpSettings',
            self::DOMAIN_APPLICATION => 'application',
            self::DOMAIN_DATABASE => 'database',
            self::DOMAIN_GPG => 'gpg',
            self::DOMAIN_JWT => 'jwt',
            self::DOMAIN_SSL => 'ssl',
         ];

         if (!isset($domainLegacyKeyMapping[$domain])) {
             throw new InternalErrorException(__('Legacy array key not found for "{0}" domain', $domain));
         }

         return $domainLegacyKeyMapping[$domain];
    }
}
