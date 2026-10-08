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
 * @since         5.17.0
 */
namespace Passbolt\Scim\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Core\Configure;
use Cake\Log\Log;
use Passbolt\Ee\Service\Healthcheck\EeHealthcheckServiceCollector;
use Passbolt\Scim\Service\ScimGetSettingsService;
use Passbolt\Scim\Service\ScimTokenExpiryChecker;
use Throwable;

class ScimSecretTokenExpiryWarningHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    private bool $status = false;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        try {
            $settings = (new ScimGetSettingsService())->getSettingsDecryptedValue();
        } catch (Throwable $e) {
            $this->status = true;
            if (Configure::read('debug')) {
                Log::warning('Unable to get SCIM settings. Error: ' . $e->getMessage());
            }

            return $this;
        }

        // Settings not configured, nothing to check
        if (empty($settings)) {
            $this->status = true;

            return $this;
        }

        $this->status = !(new ScimTokenExpiryChecker())->isNearExpiry($settings);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return EeHealthcheckServiceCollector::DOMAIN_SCIM;
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
        return __('The SCIM secret token is not about to expire.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The SCIM secret token will expire soon, you are requested to rotate it.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
    {
        return __('Rotate the SCIM secret token in the administration settings.');
    }

    /**
     * @inheritDoc
     */
    public function cliOption(): string
    {
        return EeHealthcheckServiceCollector::DOMAIN_SCIM;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'isScimTokenNotNearExpiry';
    }
}
