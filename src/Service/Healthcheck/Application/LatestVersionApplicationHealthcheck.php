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

namespace App\Service\Healthcheck\Application;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Service\Network\SocketService;
use Cake\Core\Configure;
use Cake\Http\Client;

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

    private Client $client;

    private SocketService $socketService;

    /**
     * @param \Cake\Http\Client $client Client.
     * @param \App\Service\Network\SocketService $socketService Socket service.
     */
    public function __construct(Client $client, SocketService $socketService)
    {
        $this->client = $client;
        $this->socketService = $socketService;
    }

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        try {
            $this->remoteVersion = $this->getLatestTagName();
            $this->status = $this->isLatestVersion();
        } catch (\Exception $e) {
            $this->exceptionThrown = true;
            $this->remoteVersion = __('undefined');
            $this->status = false;
        }

        return $this;
    }

    /**
     * Return true if the current installed version match the latest official one
     *
     * @return bool true if installed version is the latest
     */
    private function isLatestVersion(): bool
    {
        $remoteVersion = ltrim($this->remoteVersion, 'v');
        $localVersion = ltrim(Configure::read('passbolt.version'), 'v');

        return version_compare($localVersion, $remoteVersion, '>=');
    }

    /**
     * Return the current master version according to the official passbolt repository
     *
     * @throws \Cake\Network\Exception\SocketException If the github repository is not reachable
     * @throws \Exception if the tag information cannot be retrieved
     * @return string tag name such as 'v1.0.1'
     */
    private function getLatestTagName(): string
    {
        // Make sure github is reachable
        $this->socketService->canConnect(['host' => 'github.com', 'port' => 443, 'timeout' => 30]);

        $remoteTagName = Configure::read('passbolt.remote.version');
        if (is_null($remoteTagName)) {
            $results = $this->client->get('https://api.github.com/repos/passbolt/passbolt_api/releases/latest');

            $tags = json_decode($results->getStringBody(), true);
            if (!isset($tags['tag_name'])) {
                throw new \Exception(__('Could not read tag information on github repository'));
            }
            $remoteTagName = ltrim($tags['tag_name'], 'v');
            Configure::write('passbolt.remote.version', $remoteTagName);
        }

        return $remoteTagName;
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
            $msg = __('Could not connect to passbolt repository to check versions.');
            $msg .= ' ';
            $msg .= __('It is not possible to check if your version is up-to-date.');
        }

        return $msg;
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        $msg = __('See https://www.passbolt.com/help/tech/update');
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
