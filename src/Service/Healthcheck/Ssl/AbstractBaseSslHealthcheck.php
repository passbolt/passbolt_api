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

namespace App\Service\Healthcheck\Ssl;

use App\Service\Healthcheck\Core\FullBaseUrlReachableCoreHealthcheck;
use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Http\Client;
use Cake\Routing\Router;

abstract class AbstractBaseSslHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    protected FullBaseUrlReachableCoreHealthcheck $fullBaseUrlReachableCoreHealthcheck;

    /**
     * HTTP Client.
     *
     * @var \Cake\Http\Client
     */
    protected Client $client;

    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    protected bool $status = false;

    /**
     * @var array Help message
     */
    protected array $helpMessage = [];

    /**
     * Options passed to the client
     *
     * @return array
     */
    abstract protected function getClientOptions(): array;

    /**
     * @param \App\Service\Healthcheck\Core\FullBaseUrlReachableCoreHealthcheck $fullBaseUrlReachableCoreHealthcheck checks if the base URl is reachable at all
     * @param \Cake\Http\Client $client client
     */
    public function __construct(
        FullBaseUrlReachableCoreHealthcheck $fullBaseUrlReachableCoreHealthcheck,
        Client $client
    ) {
        $this->fullBaseUrlReachableCoreHealthcheck = $fullBaseUrlReachableCoreHealthcheck;
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        if ($this->fullBaseUrlReachableCoreHealthcheck->isHealthcheckEndpointUnreachable()) {
            return $this;
        }

        $url = Router::url('/healthcheck/status.json', true);
        try {
            $response = $this->client->get($url, [], $this->getClientOptions());
            $this->status = $response->isOk();
        } catch (\Exception $e) {
            $this->helpMessage[] = $e->getMessage();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_SSL;
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
    public function getHelpMessage()
    {
        return $this->helpMessage;
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_SSL;
    }
}
