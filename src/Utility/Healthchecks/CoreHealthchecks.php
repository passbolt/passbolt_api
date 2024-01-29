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
 * @since         4.5.0
 */
namespace App\Utility\Healthchecks;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use Cake\Http\Client;
use Cake\Routing\Router;
use Cake\Validation\Validation;

class CoreHealthchecks
{
    private ?Client $client;

    /**
     * @param ?\Cake\Http\Client $client client used to query the healthcheck endpoint in tests
     */
    public function __construct(?Client $client = null)
    {
        $this->client = $client;
    }

    /**
     * Check core file configuration
     *
     * - cache: settings are set
     * - debugDisabled: the core.debug is set to 0
     * - salt: true if non default salt is used
     * - cipherSeed: true if non default cipherSeed is used
     *
     * @param array|null $checks List of checks
     * @return array
     */
    public function all(?array $checks = []): array
    {
        $settings = Cache::getConfig('_cake_core_');
        $checks['core']['cache'] = !empty($settings);
        $checks['core']['debugDisabled'] = (Configure::read('debug') === false);
        $checks['core']['salt'] = (Configure::read('Security.salt') !== '__SALT__');
        $checks['core']['fullBaseUrl'] = (Configure::read('App.fullBaseUrl') !== null);
        $checks['core']['validFullBaseUrl'] = Validation::url(Configure::read('App.fullBaseUrl'), true);
        $checks['core']['info']['fullBaseUrl'] = Configure::read('App.fullBaseUrl');

        // Check if the URL is reachable
        $checks['core']['fullBaseUrlReachable'] = false;
        try {
            $isResponseOK = false;
            $url = Router::url('/healthcheck/status.json', true);
            if (isset($this->client)) {
                // If the client is mocked in tests
                $isResponseOK = $this->client->get($url)->isOk();
            } else {
                $context = stream_context_create([
                    'http' => [
                        'method' => 'GET',
                    ],
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]);
                $response = @file_get_contents($url, false, $context); // phpcs:ignore
                if ($response !== false && !empty($response)) {
                    $json = json_decode($response);
                    if (isset($json->body)) {
                        $isResponseOK = ($json->body === 'OK');
                    }
                }
            }
            $checks['core']['fullBaseUrlReachable'] = $isResponseOK;
        } catch (CakeException $e) {
        }

        return $checks;
    }
}
