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
 * @since         4.10.0
 */

namespace App\Service\Healthcheck\Environment;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Core\Configure;
use Symfony\Component\Process\Process;

class TimeSyncHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    protected const COMMAND = 'timedatectl | grep -i -A 1 clock';
    protected const URL_HELP = 'https://www.passbolt.com/docs/hosting/configure/ntp/';

    protected const KEY_CLOCK = 'clock';
    protected const KEY_NTP = 'ntp';

    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    protected bool $status = false;

    /**
     * Result of the check run. Null if not possible to detect using `timedatectl` command.
     *
     * @var array<string, bool>
     */
    protected array $result = [];

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $this->result = $this->syncCheck();
        $this->status = $this->result && $this->result[static::KEY_CLOCK] && $this->result[static::KEY_NTP];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_ENVIRONMENT;
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
        if ($this->result) {
            return $this->determineErrorLevel($this->result);
        }

        $levelForNotExistingCommand = HealthcheckServiceCollector::LEVEL_WARNING;
        if (Configure::read('debug')) {
            $levelForNotExistingCommand = HealthcheckServiceCollector::LEVEL_NOTICE;
        }

        return $levelForNotExistingCommand;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('System clock is synchronized and NTP service is active.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        if (!$this->result) {
            return __('System clock and NTP service information cannot be found.');
        }

        if (!$this->result[static::KEY_CLOCK] && $this->result[static::KEY_NTP]) {
            return __('NTP service is active but the system clock is not synchronized.');
        }
        if ($this->result[static::KEY_CLOCK] && !$this->result[static::KEY_NTP]) {
            return __('System clock is synchronized but NTP service is inactive.');
        }

        return __('System clock is not synchronized and NTP service is inactive.');
    }

    /**
     * @return array<string>
     */
    public function getHelpMessage(): array
    {
        $message = [];
        if ($this->result && !$this->result[static::KEY_CLOCK] && $this->result[static::KEY_NTP]) {
            $message[] = __('That generally means the NTP service is failing to contact any NTP servers.');
        }

        $message[] = __('See `{0}`. More information: {1}', static::COMMAND, static::URL_HELP);

        return $message;
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_ENVIRONMENT;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'timeSync';
    }

    /**
     * @return array<string, bool>
     */
    protected function syncCheck(): array
    {
        $result = $this->runCommand(static::COMMAND);
        if ($result === null) {
            return [];
        }

        return [
            static::KEY_CLOCK => strpos($result, 'clock synchronized: yes') !== false,
            static::KEY_NTP => strpos($result, 'NTP service: active') !== false,
        ];
    }

    /**
     * @param string $command Command to run.
     * @return string|null
     */
    protected function runCommand(string $command): ?string
    {
        $process = Process::fromShellCommandLine($command);
        $process->run();
        if (!$process->isSuccessful()) {
            return null;
        }

        return trim($process->getOutput()) ?: null;
    }

    /**
     * @param array<string, bool> $result Result
     * @return string
     */
    protected function determineErrorLevel(array $result): string
    {
        if ($result[static::KEY_CLOCK] && !$result[static::KEY_NTP]) {
            return HealthcheckServiceCollector::LEVEL_WARNING;
        }

        return HealthcheckServiceCollector::LEVEL_ERROR;
    }
}
