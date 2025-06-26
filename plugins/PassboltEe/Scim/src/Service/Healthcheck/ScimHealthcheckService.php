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
 * @since         4.1.0
 */
namespace Passbolt\Scim\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Utility\Application\FeaturePluginAwareTrait;
use Passbolt\Ee\Service\Healthcheck\EeHealthcheckServiceCollector;

class ScimHealthcheckService implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    use FeaturePluginAwareTrait;

    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    /**
     * Checks if the plugin is enabled
     *
     * @return bool
     */
    protected function isScimPluginEnabled(): bool
    {
        return $this->isFeaturePluginEnabled('Scim');
    }

    /**
     * Performs the actual check and returns itself.
     *
     * @return $this
     */
    public function check(): HealthcheckServiceInterface
    {
        $this->status =  $this->isScimPluginEnabled();

        return $this;
    }

    /**
     * Health check domain key this check belongs to.
     *
     * @return string
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
        return HealthcheckServiceCollector::LEVEL_NOTICE;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('SCIM plugin is enabled.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('SCIM plugin is disabled.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
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
        return EeHealthcheckServiceCollector::DOMAIN_SCIM;
    }

    /**
     * Returns the array key used when returning check result.
     *
     * @deprecated As of v4.7.0, this is mostly used to keep BC.
     * @return string
     */
    public function getLegacyArrayKey(): string
    {
        return 'isScimPluginEnabled';
    }
}
