<?php
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

use Cake\Core\Configure;
use Cake\Http\Client;

class SslHealthchecks
{
    /**
     * Run all SSL healthchecks
     *
     * @param array $checks List of checks
     * @return array
     */
    public static function all($checks = [])
    {
        $checks['ssl'] = [
            'peerValid' => false,
            'hostValid' => false,
            'notSelfSigned' => false
        ];
        // No point to check anything if this Core config is not valid
        if (isset($checks['core']['fullBaseUrlReachable'])) {
            $reachable = $checks['core']['fullBaseUrlReachable'];
        }
        if (isset($reachable) && !$reachable) {
            return $checks;
        }
        $checks = self::peerValid($checks);
        $checks = self::hostValid($checks);
        $checks = self::notSelfSigned($checks);

        return $checks;
    }

    /**
     * Check if peer is valid
     *
     * @param array $checks List of checks
     * @return array
     */
    public static function peerValid($checks = [])
    {
        $url = Configure::read('App.fullBaseUrl') . '/healthcheck/status.json';
        try {
            $HttpSocket = new Client([
                'ssl_verify_peer' => true,
                'ssl_verify_host' => false,
                'ssl_allow_self_signed' => true
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
     * @param array $checks List of checks
     * @return array
     */
    public static function hostValid($checks = [])
    {
        $url = Configure::read('App.fullBaseUrl') . '/healthcheck/status.json';
        try {
            $HttpSocket = new Client([
                'ssl_verify_peer' => true,
                'ssl_verify_host' => true,
                'ssl_allow_self_signed' => true
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
     * @param array $checks List of checks
     * @return array
     */
    public static function notSelfSigned($checks = [])
    {
        $url = Configure::read('App.fullBaseUrl') . '/healthcheck/status.json';
        try {
            $HttpSocket = new Client([
                'ssl_verify_peer' => true,
                'ssl_verify_host' => true,
                'ssl_allow_self_signed' => false
            ]);
            $response = $HttpSocket->get($url);
            $checks['ssl']['notSelfSigned'] = $response->isOk();
        } catch (\Exception $e) {
        }

        return $checks;
    }
}
