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

namespace App\Service\Healthcheck\ConfigFiles;

use App\Service\Healthcheck\HealthcheckApiSerializerService;
use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Service\Healthcheck\SkipHealthcheckInterface;
use Cake\Core\Configure;
use Closure;

class ExcludedHealthchecksConfigurationHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface, SkipHealthcheckInterface // phpcs:ignore
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
    private array $unmatchedConfigurationValues = [];

    /**
     * @var array<string>
     */
    private array $invalidFormattedSettings = [];

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
        $this->invalidFormattedSettings = $this->getInvalidFormatSettings();
        if (!empty($this->invalidFormattedSettings)) {
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
        $unknownKeys = array_diff($excludedKeys, $collector->getKeysInCollectedServices());
        $unknownDomains = array_diff($excludedDomains, $collector->getDomainsInCollectedServices());
        $this->unmatchedConfigurationValues = array_values(array_unique(array_merge($unknownKeys, $unknownDomains)));

        $this->status = empty($this->unmatchedConfigurationValues);

        return $this;
    }

    /**
     * Returns the exclusion settings set to something else than a string. An unset setting is null
     * and is not one of them.
     *
     * @return array<string>
     */
    private function getInvalidFormatSettings(): array
    {
        $invalidFormatSettings = [];

        $settings = [
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_KEYS,
            HealthcheckApiSerializerService::CONFIG_EXCLUDED_DOMAINS,
        ];
        foreach ($settings as $setting) {
            $value = Configure::read($setting);
            if ($value !== null && !is_string($value)) {
                $invalidFormatSettings[] = $setting;
            }
        }

        return $invalidFormatSettings;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_CONFIG_FILES;
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

        foreach ($this->invalidFormattedSettings as $setting) {
            $messages[] = __('The healthcheck exclusion setting {0} is not a space separated list of names.', $setting);
        }

        if (!empty($this->unmatchedConfigurationValues)) {
            $messages[] = __(
                'The healthcheck exclusion configuration contains values matching no healthcheck on this instance: {0}.', // phpcs:ignore
                implode(', ', $this->unmatchedConfigurationValues),
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
            __(
                'Check the values of {0} and {1} in {2}.',
                'passbolt.plugins.healthcheck.excludedKeys',
                'passbolt.plugins.healthcheck.excludedDomains',
                CONFIG . 'passbolt.php',
            ),
        ];

        if (!empty($this->invalidFormattedSettings)) {
            $help[] = __(
                'An exclusion setting must be a single string holding the names separated by spaces, for example "{0}".', // phpcs:ignore
                'php-version distribution',
            );
            $help[] = __('An array, a boolean or a integer is not a supported value for these settings.');
        }

        if (!empty($this->unmatchedConfigurationValues)) {
            $help[] = __('Value must be a healthcheck key, as returned by getKey(), or a healthcheck domain.');
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
        return HealthcheckServiceCollector::DOMAIN_CONFIG_FILES;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'excludedHealthchecksConfiguration';
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
        return 'excluded-healthchecks-configuration';
    }
}
