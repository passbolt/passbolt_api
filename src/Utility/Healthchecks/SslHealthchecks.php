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
 * @since         2.1.2
 */
namespace App\Utility\Healthchecks;

use Cake\Http\Client;
use Cake\Routing\Router;

class SslHealthchecks
{
    private ?Client $client;

    /**
     * @param ?\Cake\Http\Client $client client used to query the healthcheck endpoint
     */
    public function __construct(?Client $client = null)
    {
        $this->client = $client;
    }

    /**
     * Run all SSL healthchecks
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public function all(?array $checks = []): array
    {
        $checks['ssl'] = [
            'peerValid' => false,
            'hostValid' => false,
            'notSelfSigned' => false,
        ];
        // No point to check anything if this Core config is not valid
        if (isset($checks['core']['fullBaseUrlReachable'])) {
            $reachable = $checks['core']['fullBaseUrlReachable'];
        }
        if (isset($reachable) && !$reachable) {
            return $checks;
        }
        $checks = $this->peerValid($checks);
        $checks = $this->hostValid($checks);
        $checks = $this->notSelfSigned($checks);

        return $checks;
    }

    /**
     * Check if peer is valid
     *
     * @param array|null $checks List of checks
     * @return array
     */
    private function peerValid(?array $checks = []): array
    {
        $url = Router::url('/healthcheck/status.json', true);
        try {
            $HttpSocket = $this->client ?? new Client([
                'ssl_verify_peer' => true,
                'ssl_verify_host' => false,
                'ssl_allow_self_signed' => true,
            ]);
            $response = $HttpSocket->get($url);
            $checks['ssl']['peerValid'] = $response->isOk();
        } catch (\Exception $e) {
            $checks['ssl']['info'] = $e->getMessage();
        }

        return $checks;
    }

    /**
     * Check if the host is valid
     *
     * @param array|null $checks List of checks
     * @return array
     */
    private function hostValid(?array $checks = []): array
    {
        $url = Router::url('/healthcheck/status.json', true);
        try {
            $HttpSocket = $this->client ?? new Client([
                'ssl_verify_peer' => true,
                'ssl_verify_host' => true,
                'ssl_allow_self_signed' => true,
            ]);
            $response = $HttpSocket->get($url);
            $checks['ssl']['hostValid'] = $response->isOk();
        } catch (\Exception $e) {
            $checks['ssl']['info'] = $e->getMessage();
        }

        return $checks;
    }

    /**
     * Check that the certificate is not self signed
     *
     * @param array|null $checks List of checks
     * @psalm-suppress InvalidNullableReturnType false positive
     * @return array
     */
    private function notSelfSigned(?array $checks = []): array
    {
        $url = Router::url('/healthcheck/status.json', true);
        try {
            $HttpSocket = $this->client ?? new Client([
                'ssl_verify_peer' => true,
                'ssl_verify_host' => true,
                'ssl_allow_self_signed' => false,
            ]);
            $response = $HttpSocket->get($url);
            $checks['ssl']['notSelfSigned'] = $response->isOk();
        } catch (\Exception $e) {
        }

        /** @psalm-suppress NullableReturnStatement false positive  */
        return $checks;
    }
}
