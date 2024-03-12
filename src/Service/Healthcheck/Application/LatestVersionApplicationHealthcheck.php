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

namespace App\Service\Healthcheck\Application;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Utility\Migration;
use Cake\Core\Configure;

class LatestVersionApplicationHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    /**
     * Current master version according to the official passbolt repository.
     *
     * @var string
     */
    private string $remoteVersion;

    /**
     * @var bool
     */
    private bool $exceptionThrown = false;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        try {
            $this->remoteVersion = Migration::getLatestTagName();
            $this->status = Migration::isLatestVersion();
        } catch (\Exception $e) {
            $this->exceptionThrown = true;
            $this->remoteVersion = __('undefined');
            $this->status = false;
        }

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
        $msg = __('Using latest passbolt version ({0}).', Configure::read('passbolt.version'));
        if ($this->exceptionThrown) {
            $msg = __('Could connect to passbolt repository to check versions.');
        }

        return $msg;
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        $msg = __(
            'This installation is not up to date. Currently using {0} and it should be {1}.',
            Configure::read('passbolt.version'),
            $this->remoteVersion
        );
        if ($this->exceptionThrown) {
            $msg = __('Could not connect to passbolt repository to check versions');
            $msg .= ' ';
            $msg .= __('It is not possible check if your version is up to date.');
        }

        return $msg;
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        $msg = __('See. https://www.passbolt.com/help/tech/update');
        if ($this->exceptionThrown) {
            $msg = __('Check the network configuration to allow this script to check for updates.');
        }

        return $msg;
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
     * @return bool
     */
    public function isExceptionThrown(): bool
    {
        return $this->exceptionThrown;
    }

    /**
     * @return string
     */
    public function getRemoteVersion(): string
    {
        return $this->remoteVersion;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'latestVersion';
    }
}
