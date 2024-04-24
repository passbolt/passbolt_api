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

namespace Passbolt\Sso\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Core\Configure;
use Passbolt\Ee\Service\Healthcheck\EeHealthcheckServiceCollector;

class SslHostVerificationSsoHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
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
        $this->status = Configure::read('passbolt.security.sso.sslVerify', false);

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
        return __('SSL certification validation for SSO instance is enabled.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('SSL certification validation for SSO instance is disabled.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return [
            __('Disabling the ssl verify check can lead to security attacks.'),
            __('Attacker can alter the certificate and can perform man-in-the-middle attack.'),
            __('To fix this, you can set PASSBOLT_SECURITY_SSO_SSL_VERIFY environment variable to true.'),
            __('Or set passbolt.security.sso.sslVerify to true in {0}.', CONFIG . 'passbolt.php'),
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
        return 'sslHostVerification';
    }
}
