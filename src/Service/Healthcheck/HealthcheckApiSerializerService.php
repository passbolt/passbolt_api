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
 * @since         5.16.0
 */
namespace App\Service\Healthcheck;

use Cake\Core\Configure;

class HealthcheckApiSerializerService
{
    public const STATUS_PASS = 'pass';
    public const STATUS_ERROR = 'error';
    public const STATUS_WARNING = 'warning';
    public const STATUS_INFO = 'info';

    public const CONFIG_EXCLUDED_KEYS = 'passbolt.plugins.healthcheck.excludedKeys';
    public const CONFIG_EXCLUDED_DOMAINS = 'passbolt.plugins.healthcheck.excludedDomains';

    /**
     * @var \App\Service\Healthcheck\HealthcheckServiceCollector
     */
    private HealthcheckServiceCollector $collector;

    /**
     * @param \App\Service\Healthcheck\HealthcheckServiceCollector $collector Used to resolve domain display titles.
     */
    public function __construct(HealthcheckServiceCollector $collector)
    {
        $this->collector = $collector;
    }

    /**
     * Runs every registered healthcheck, excludes the checks excluded by configuration and the skipped ones,
     * and serializes the results as a flat list, in registration order.
     *
     * @return array
     */
    public function serialize(): array
    {
        $body = [];
        $excludedKeys = self::readExcludedConfig(self::CONFIG_EXCLUDED_KEYS);
        $excludedDomains = self::readExcludedConfig(self::CONFIG_EXCLUDED_DOMAINS);

        foreach ($this->collector->getServices() as $healthcheckService) {
            if (
                in_array($healthcheckService->getKey(), $excludedKeys, true)
                || in_array($healthcheckService->domain(), $excludedDomains, true)
            ) {
                continue;
            }

            $healthcheck = $healthcheckService->check();

            if ($healthcheck instanceof SkipHealthcheckInterface && $healthcheck->isSkipped()) {
                continue;
            }

            $domain = $healthcheck->domain();

            $body[] = [
                'key' => $healthcheck->getKey(),
                'domain_key' => $domain,
                'domain' => [
                    'key' => $domain,
                    'title' => $this->collector->getTitleFromDomain($domain),
                ],
                'status' => $this->status($healthcheck),
                'message' => $this->message($healthcheck),
                'help' => $this->help($healthcheck),
            ];
        }

        return $body;
    }

    /**
     * Reads a space separated exclusion list from the configuration.
     *
     * An unset, empty or non-string value excludes nothing. Unrecognised entries are ignored.
     *
     * @param string $configKey The configuration key holding the list.
     * @return array<string>
     */
    public static function readExcludedConfig(string $configKey): array
    {
        $value = Configure::read($configKey);

        if (!is_string($value) || trim($value) === '') {
            return [];
        }

        return preg_split('/\s+/', trim($value)) ?: [];
    }

    /**
     * if level() is NOTICE then it is an INFO
     * if level() is WARNING or ERROR only matters if it fails
     * if fails and level() is WARNING then status is a WARNING
     *
     * @param \App\Service\Healthcheck\HealthcheckServiceInterface $result An executed healthcheck result.
     * @return string
     */
    private function status(HealthcheckServiceInterface $result): string
    {
        if ($result->level() === HealthcheckServiceCollector::LEVEL_NOTICE) {
            return self::STATUS_INFO;
        }

        if ($result->isPassed()) {
            return self::STATUS_PASS;
        }

        if ($result->level() === HealthcheckServiceCollector::LEVEL_WARNING) {
            return self::STATUS_WARNING;
        }

        return self::STATUS_ERROR;
    }

    /**
     * @param \App\Service\Healthcheck\HealthcheckServiceInterface $result An executed healthcheck result.
     * @return string
     */
    private function message(HealthcheckServiceInterface $result): string
    {
        return $result->isPassed()
            ? $result->getSuccessMessage()
            : $result->getFailureMessage();
    }

    /**
     * Help is only emitted if failure.
     * Normalised to an array because implementations return string, array or null.
     *
     * @param \App\Service\Healthcheck\HealthcheckServiceInterface $result An executed healthcheck result.
     * @return array
     */
    private function help(HealthcheckServiceInterface $result): array
    {
        if ($result->isPassed()) {
            return [];
        }

        $help = $result->getHelpMessage();

        if ($help === null) {
            return [];
        }

        return is_array($help) ? array_values($help) : [$help];
    }
}
