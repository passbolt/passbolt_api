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

namespace App\Service\Healthcheck\Core;

use App\Service\Healthcheck\HealthcheckApiSerializerService;
use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Service\Healthcheck\SkipHealthcheckInterface;
use Cake\Core\Configure;
use Closure;

class ExcludedHealthchecksCoreHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface, SkipHealthcheckInterface // phpcs:ignore
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
     * @var array<string>
     */
    private array $unmatchedCoreKeys = [];

    /**
     * @var array<string>
     */
    private array $unmatchedCoreDomains = [];

    /**
     * @var bool
     */
    private bool $isInvalidKeySettings = false;

    /**
     * @var bool
     */
    private bool $isInvalidDomainSettings = false;

    /**
     * @var \Closure(): \App\Service\Healthcheck\HealthcheckServiceCollector
     */
    private Closure $collectorProvider;

    /**
     * @param \Closure(): \App\Service\Healthcheck\HealthcheckServiceCollector $collectorProvider Lazily resolves the collector this check reads.
     */
    public function __construct(Closure $collectorProvider)
    {
        $this->collectorProvider = $collectorProvider;
    }

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $this->isInvalidKeySettings = $this->hasInvalidFormat(
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS,
        );
        $this->isInvalidDomainSettings = $this->hasInvalidFormat(
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS,
        );
        if ($this->isInvalidKeySettings || $this->isInvalidDomainSettings) {
            return $this;
        }

        $excludedKeys = HealthcheckApiSerializerService::readExcludedConfig(
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS,
        );
        $excludedDomains = HealthcheckApiSerializerService::readExcludedConfig(
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS,
        );

        if (empty($excludedKeys) && empty($excludedDomains)) {
            $this->markAsSkipped();

            return $this;
        }

        $collector = ($this->collectorProvider)();
        $this->unmatchedCoreKeys = array_values(array_diff(
            $excludedKeys,
            $this->getKeysInCollectedServices($collector),
        ));
        $this->unmatchedCoreDomains = array_values(array_diff(
            $excludedDomains,
            $collector->getDomainsInCollectedServices(),
        ));

        $this->status = empty($this->unmatchedCoreKeys) && empty($this->unmatchedCoreDomains);

        return $this;
    }

    /**
     * Returns the unique keys of every healthcheck registered in the given collector.
     *
     * @param \App\Service\Healthcheck\HealthcheckServiceCollector $collector The collector to read the keys from.
     * @return array<string>
     */
    private function getKeysInCollectedServices(HealthcheckServiceCollector $collector): array
    {
        $keys = [];
        foreach ($collector->getServices() as $service) {
            if (!in_array($service->getKey(), $keys)) {
                $keys[] = $service->getKey();
            }
        }

        return $keys;
    }

    /**
     * Returns whether the given exclusion setting is set to something else than a list of names.
     * An unset setting is null and is not one of them.
     *
     * @param string $setting The configuration path of the exclusion setting to read.
     * @return bool
     */
    private function hasInvalidFormat(string $setting): bool
    {
        $value = Configure::read($setting);

        if ($value === null || is_string($value)) {
            return false;
        }

        if (!is_array($value)) {
            return true;
        }

        return $value !== array_filter($value, 'is_string');
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_CORE;
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
        return __('The healthcheck exclusion configuration is valid.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        $messages = [];

        if ($this->isInvalidKeySettings) {
            $messages[] = __(
                'The healthcheck exclusion setting {0} is not a valid list of healthcheck names.',
                HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS,
            );
        }

        if ($this->isInvalidDomainSettings) {
            $messages[] = __(
                'The healthcheck exclusion setting {0} is not a valid list of healthcheck names.',
                HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS,
            );
        }

        if (!empty($this->unmatchedCoreKeys) || !empty($this->unmatchedCoreDomains)) {
            $messages[] = __(
                'The healthcheck exclusion configuration contains values matching no healthcheck on this instance.',
            );
        }

        return implode(' ', $messages);
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
    {
        $help = [
            __('Check the healthcheck exclusion settings in {0}.', CONFIG . 'passbolt.php'),
            __(
                'Or set the {0} and {1} environment variables.',
                'PASSBOLT_PLUGINS_HEALTHCHECK_EXCLUDED_KEYS',
                'PASSBOLT_PLUGINS_HEALTHCHECK_EXCLUDED_DOMAINS',
            ),
        ];

        $invalidSettings = [];
        if ($this->isInvalidKeySettings) {
            $invalidSettings[] = HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS;
        }
        if ($this->isInvalidDomainSettings) {
            $invalidSettings[] = HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS;
        }

        if (!empty($invalidSettings)) {
            $help[] = __(
                'The value must be a string of names separated by spaces, or an array of names. {0} is not valid.', // phpcs:ignore
                implode(', ', $invalidSettings),
            );
        }

        if (!empty($this->unmatchedCoreKeys)) {
            $help[] = __(
                'The value must be a valid key. {0} not valid.',
                implode(', ', $this->unmatchedCoreKeys),
            );
        }

        if (!empty($this->unmatchedCoreDomains)) {
            $help[] = __(
                'The value must be a valid domain. {0} not valid.',
                implode(', ', $this->unmatchedCoreDomains),
            );
        }

        return $help;
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_CORE;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'excludedHealthchecks';
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

    /**
     * @inheritDoc
     */
    public function getKey(): string
    {
        return 'excluded-healthchecks';
    }
}
