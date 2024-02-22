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
 * @since         4.6.0
 */

namespace App\Service\Healthcheck\Environment;

use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Utility\Healthchecks;
use Cake\Core\Configure;

class NextMinPhpVersionHealthcheck implements HealthcheckServiceInterface
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
        $this->status = version_compare(
            PHP_VERSION,
            Configure::read(Healthchecks::PHP_NEXT_MIN_VERSION_CONFIG),
            '>='
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        // TODO: Use a constant
        return 'environment';
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
        return 'warning';
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('PHP version is {0} or above.', Configure::read(Healthchecks::PHP_NEXT_MIN_VERSION_CONFIG));
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __(
            'PHP version less than {0} will soon be not supported by passbolt, so consider upgrading your operating system or PHP environment.', // phpcs:ignore
            Configure::read(Healthchecks::PHP_NEXT_MIN_VERSION_CONFIG)
        );
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
        return 'environment';
    }
}
