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
 * @since         4.8.0
 */

namespace Passbolt\SmtpSettings\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Http\Exception\BadRequestException;
use Passbolt\SmtpSettings\Service\SmtpSettingsSslOptionsGetService;

class CustomSslOptionsSmtpSettingsHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    /**
     * @var array
     */
    private array $sslOptions = [];

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $service = new SmtpSettingsSslOptionsGetService();

        try {
            $this->sslOptions = $service->get();
            $default = $service->isDefault();

            if ($default) {
                $this->status = true;

                return $this;
            }
        } catch (BadRequestException $e) {
            // Fail when config values are invalid
            $this->status = false;
            $this->sslOptions = [];
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_SMTP_SETTINGS;
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
        $level = HealthcheckServiceCollector::LEVEL_WARNING;

        if (!$this->status && !empty($this->sslOptions) && !$this->isSslVerificationDisabled($this->sslOptions)) {
            $level = HealthcheckServiceCollector::LEVEL_NOTICE;
        } elseif (!$this->status && empty($this->sslOptions)) {
            $level = HealthcheckServiceCollector::LEVEL_ERROR;
        }

        return $level;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('No custom SSL configuration for SMTP server.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        $failureMessage = __('Custom SSL certificate options for SMTP server is in use.');
        if (empty($this->sslOptions)) {
            $failureMessage = __('Custom SSL configuration options set are invalid.');
        } elseif ($this->isSslVerificationDisabled($this->sslOptions)) {
            $failureMessage = __('SSL certification validation for SMTP server is disabled.');
        }

        return $failureMessage;
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): ?string
    {
        return null;
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_SMTP_SETTINGS;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'customSslOptions';
    }

    /**
     * @param array $sslOptions SSL options to check.
     * @return bool
     */
    private function isSslVerificationDisabled(array $sslOptions): bool
    {
        return (isset($sslOptions['verify_peer']) && !$sslOptions['verify_peer'])
            || (isset($sslOptions['verify_peer_name']) && !$sslOptions['verify_peer_name']);
    }
}
