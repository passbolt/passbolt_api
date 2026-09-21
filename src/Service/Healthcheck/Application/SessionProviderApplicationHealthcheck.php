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

namespace App\Service\Healthcheck\Application;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Core\Configure;

class SessionProviderApplicationHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    /**
     * Configuration constants.
     */
    public const SESSION_PROVIDER_CONFIG = 'Session.defaults';

    public const SESSION_PROVIDER_CONFIG_PATH = CONFIG . 'passbolt.php';

    public const SESSION_PROVIDERS_AVAILABLE = ['php', 'cake', 'database', 'cache'];

    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    /**
     * The session provider that the application is using.
     *
     * @var string
     */
    private string $provider;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $this->provider = Configure::read(self::SESSION_PROVIDER_CONFIG);

        $this->status = in_array(
            $this->provider,
            self::SESSION_PROVIDERS_AVAILABLE,
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_APPLICATION;
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
        return __(
            'The session provider is {0}.',
            $this->provider,
        );
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __(
            'The session provider {0} is not supported.',
            $this->provider,
        );
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
    {
        return [
            __(
                'The session providers supported are: {0}.',
                implode(', ', self::SESSION_PROVIDERS_AVAILABLE),
            ),
            __(
                'Define it using the SESSION_DEFAULTS environment variable or under {0} in {1}.',
                self::SESSION_PROVIDER_CONFIG,
                self::SESSION_PROVIDER_CONFIG_PATH,
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
        return HealthcheckServiceCollector::DOMAIN_APPLICATION;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'sessionProvider';
    }
}
