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
 * @since         5.18.0
 */

namespace Passbolt\MultiFactorAuthentication\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Http\Exception\InternalErrorException;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoVerifySubscriberMfaHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    protected const URL_HELP = 'https://www.passbolt.com/docs/admin/authentication/mfa/duo/';

    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    /**
     * If DUO provider is enabled or not.
     *
     * @var bool
     */
    private bool $providerEnabled = true;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        try {
            $mfaOrgSettings = MfaOrgSettings::get();
        } catch (InternalErrorException) {
            $this->status = true;
            $this->providerEnabled = false;

            return $this;
        }

        if (!$mfaOrgSettings->isProviderEnabled(MfaSettings::PROVIDER_DUO)) {
            $this->status = true;
            $this->providerEnabled = false;

            return $this;
        }

        $this->status = $mfaOrgSettings->getDuoOrgSettings()->isVerifySubscriberEnabled();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_MFA;
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
        if (!$this->providerEnabled) {
            return __('Duo is not enabled, the Duo subscriber verification does not apply.');
        }

        return __('Duo subscriber verification is enabled.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('Duo is enabled but the Duo subscriber verification is disabled.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
    {
        return [
            __('Enable it in Administration > Multi-factor authentication > Duo.'),
            __('Users whose Duo account does not match their passbolt username will be locked out.'),
            __('Read the documentation before enabling it: {0}', static::URL_HELP),
        ];
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_MFA;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'duoVerifySubscriberEnabled';
    }

    /**
     * @inheritDoc
     */
    public function getKey(): string
    {
        return 'duo-verify-subscriber-enabled';
    }
}
