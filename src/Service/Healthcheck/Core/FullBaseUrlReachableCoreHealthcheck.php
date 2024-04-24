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

namespace App\Service\Healthcheck\Core;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Http\Client;
use Cake\Routing\Router;

class FullBaseUrlReachableCoreHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    private bool $isHealthcheckEndpointUnreachable = false;

    /**
     * HTTP Client.
     *
     * @var \Cake\Http\Client
     */
    private Client $client;

    /**
     * @param \Cake\Http\Client $client HTTP Client.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * In case the full base URL was found as unreachable, the
     * result is cached in this variable, to be accessible by other services
     * and to avoid unnecessary redundant curls.
     *
     * @return bool
     */
    public function isHealthcheckEndpointUnreachable(): bool
    {
        return $this->isHealthcheckEndpointUnreachable;
    }

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        if ($this->isHealthcheckEndpointUnreachable()) {
            return $this;
        }

        try {
            $url = Router::url('/healthcheck/status.json', true);

            $options = [
                'ssl_verify_peer' => false,
                'ssl_verify_peer_name' => false,
                'ssl_verify_host' => false,
            ];
            $response = $this->client->get($url, [], $options)->getJson();
            if (isset($response['body'])) {
                $this->status = ($response['body'] === 'OK');
            }
        } catch (\Throwable $e) {
            // Nothing to do here
        } finally {
            if ($this->status !== true) {
                $this->isHealthcheckEndpointUnreachable = true;
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_CORE;
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
        return __('/healthcheck/status is reachable.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('Could not reach the /healthcheck/status with the url specified in App.fullBaseUrl');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return [
            __('Check that the domain name is correct in {0}', CONFIG . 'passbolt.php'),
            __('Check the network settings'),
        ];
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_CORE;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'fullBaseUrlReachable';
    }
}
