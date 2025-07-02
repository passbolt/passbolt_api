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

namespace App\Service\Healthcheck\Core;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Cache\Cache;

class CacheCoreHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
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
    private bool $isCacheCoreConfigPresent = false;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $this->isCacheCoreConfigPresent = Cache::getConfig('_cake_core_') !== null;
        $cacheTranslationsConfig = !empty(Cache::getConfig('_cake_translations_'));

        $this->status = !$this->isCacheCoreConfigPresent && $cacheTranslationsConfig;

        return $this;
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
        return $this->isCacheCoreConfigPresent ? HealthcheckServiceCollector::LEVEL_WARNING : HealthcheckServiceCollector::LEVEL_ERROR; // phpcs:ignore
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('Cache is working.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        $failureMessage = __('Cache is NOT working.');
        if ($this->isCacheCoreConfigPresent) {
            $failureMessage = __('Deprecated `_cake_core_` cache configuration found.');
        }

        return $failureMessage;
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
    {
        $helpMessage = __('Check the settings in {0}', CONFIG . 'app.php');
        if ($this->isCacheCoreConfigPresent) {
            $helpMessage = __('Replace `_cake_core_` with `_cake_translations_` in your {0} configuration file.', CONFIG . 'app.php'); // phpcs:ignore
        }

        return $helpMessage;
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
        return 'cache';
    }
}
