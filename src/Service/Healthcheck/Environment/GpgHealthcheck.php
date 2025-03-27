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
use App\Utility\CommandRunner;

class GpgHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    private const COMMAND_GPG = 'gpg --version | grep gpg';
    private const COMMAND_LIBGCRYPT = 'gpg --version | grep libgcrypt';

    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    private ?string $gpgVersion = null;

    private ?string $libgcryptVersion = null;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $this->gpgVersion = $this->runCommand(self::COMMAND_GPG);
        $this->libgcryptVersion = $this->runCommand(self::COMMAND_LIBGCRYPT);
        $this->status = $this->gpgVersion !== null && $this->libgcryptVersion !== null;

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
        return $this->gpgVersion . ' / ' . $this->libgcryptVersion;
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('Cannot detect your `gpg` or `libgcrypt` version.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
    {
        return [__('See `{0}` and `{1}`.', self::COMMAND_GPG, self::COMMAND_LIBGCRYPT)];
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
        return 'gpg';
    }

    /**
     * @param string $command Command to run.
     * @return string|null
     */
    protected function runCommand(string $command): ?string
    {
        $process = CommandRunner::run($command);
        if (!$process || !$process->isSuccessful()) {
            return null;
        }

        return trim($process->getOutput()) ?: null;
    }
}
