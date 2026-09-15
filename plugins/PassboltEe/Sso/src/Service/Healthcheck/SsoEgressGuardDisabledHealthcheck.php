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
 * @since         5.15.0
 */

namespace Passbolt\Sso\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Passbolt\Ee\Service\Healthcheck\EeHealthcheckServiceCollector;
use Passbolt\Sso\Utility\Http\SsoEgressGuard;

class SsoEgressGuardDisabledHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $this->status = (new SsoEgressGuard())->isEnabled();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return EeHealthcheckServiceCollector::DOMAIN_SSO;
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
        return __('The SSO provider URL egress guard is enabled.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The SSO provider URL egress guard is disabled and will be enabled by default in a future version.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
    {
        return [
            __('The egress guard prevents the server from connecting to internal addresses through SSO provider URLs.'),
            __('It is disabled by default in this version but will be enabled by default in a future version.'),
            __('Enable it now to verify your SSO configuration works with the guard before it becomes the default.'),
            __('Set the PASSBOLT_SECURITY_SSO_EGRESS_GUARD_ENABLED environment variable to true.'),
            __('Or set {0} to true in {1}.', SsoEgressGuard::CONFIG_ENABLED, CONFIG . 'passbolt.php'),
        ];
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return EeHealthcheckServiceCollector::DOMAIN_SSO;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'egressGuardEnabled';
    }

    /**
     * @inheritDoc
     */
    public function getKey(): string
    {
        return 'sso-egress-guard-disabled';
    }
}
