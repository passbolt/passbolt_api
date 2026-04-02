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
 * @since         5.11.0
 */
namespace Passbolt\Scim\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\I18n\Date;
use Passbolt\Ee\Service\Healthcheck\EeHealthcheckServiceCollector;
use Passbolt\Scim\Service\ScimGetSettingsService;
use Throwable;

class ScimSecretTokenExpiryHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
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
            // If settings can't be read, skip this check (other healthchecks will catch it)
            $this->status = true;

            return $this;
        }

        // No settings configured — nothing to check
        if (empty($settings)) {
            $this->status = true;

            return $this;
        }

        $expired = $settings['expired'] ?? null;
        if ($expired === null) {
            // No expired field set (pre-migration state) — treat as not expired
            $this->status = true;

            return $this;
        }

        $this->status = !Date::now()->greaterThan(new Date($expired));

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
        return HealthcheckServiceCollector::LEVEL_ERROR;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The SCIM secret token is not expired.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The SCIM secret token is expired, you are requested to rotate it.');
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
        return 'isScimTokenNotExpired';
    }
}
