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
 * @since         5.3.2
 */

namespace Passbolt\Sso\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Core\Configure;
use Passbolt\Ee\Service\Healthcheck\EeHealthcheckServiceCollector;
use Passbolt\Sso\Middleware\SsoEndpointsSecurityMiddleware;

class SsoEditEndpointsDisabledHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
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
        $this->status = Configure::read(SsoEndpointsSecurityMiddleware::SECURITY_CONFIG_KEY, false);

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
        return __('The endpoints for updating the SSO configurations are disabled.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The endpoints for updating the SSO configurations are enabled.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
    {
        return [
            __('It is recommended to disable endpoints for updating the SSO configurations.'),
            __('Set the PASSBOLT_SECURITY_SSO_SETTINGS_EDITION_DISABLED environment variable to true.'),
            __(
                'Or set {0} to true in {1}.',
                SsoEndpointsSecurityMiddleware::SECURITY_CONFIG_KEY,
                CONFIG . 'passbolt.php'
            ),
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
        return 'settingsEditEndpointsDisabled';
    }
}
