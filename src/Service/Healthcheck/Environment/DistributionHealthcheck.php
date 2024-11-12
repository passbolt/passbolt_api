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
use Symfony\Component\Process\Process;

class DistributionHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    private const COMMAND = ['uname', '-a'];

    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    private ?string $result = null;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $this->result = $this->detectDistribution();
        $this->status = $this->result !== null;

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
        return $this->status ? HealthcheckServiceCollector::LEVEL_NOTICE : HealthcheckServiceCollector::LEVEL_WARNING;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return (string)$this->result;
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('Cannot detect your system distribution details.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return [__('See `{0}`.', implode(' ', self::COMMAND))];
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
        return 'distribution';
    }

    /**
     * @return string|null
     */
    private function detectDistribution(): ?string
    {
        $process = new Process(self::COMMAND);
        $process->run();
        if (!$process->isSuccessful()) {
            return null;
        }

        return trim($process->getOutput()) ?: null;
    }
}
