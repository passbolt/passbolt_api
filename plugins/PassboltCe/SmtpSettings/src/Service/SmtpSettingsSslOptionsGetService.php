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
namespace Passbolt\SmtpSettings\Service;

use Cake\Core\Configure;

class SmtpSettingsSslOptionsGetService
{
    /**
     * Returns empty array if values set are defaults as settings those again won't have any effect.
     * Otherwise, returns set configurations mapped as PHP SSL context options format (https://www.php.net/manual/en/context.ssl.php#refsect1-context.ssl-options).
     *
     * @return array
     */
    public function get(): array
    {
        $configSslOptions = Configure::read('passbolt.plugins.smtpSettings.security', []);

        if ($this->isDefault($configSslOptions)) {
            return [];
        }

        return $this->getMappedOptions($configSslOptions);
    }

    /**
     * Checks if SSL options set in configuration are defaults.
     *
     * @param array $configOptions SSL options set in configuration.
     * @return bool
     */
    private function isDefault(array $configOptions): bool
    {
        if (count($configOptions) !== 4) {
            return false;
        }

        $defaults = [
            'verify_peer' => true,
            'verify_peer_name' => true,
            'allow_self_signed' => false,
            'cafile' => null,
        ];

        $result = $this->getMappedOptions($configOptions);

        return empty(array_diff_assoc($defaults, $result));
    }

    /**
     * @param array $configSslOptions Config SSL options.
     * @return array
     */
    private function getMappedOptions(array $configSslOptions): array
    {
        $result = [];
        $mapping = $this->getMapping();

        foreach ($configSslOptions as $key => $value) {
            if (!isset($mapping[$key])) {
                // skip if mapping not found
                continue;
            }

            $result[$mapping[$key]] = $value;
        }

        return $result;
    }

    /**
     * @return string[]
     */
    private function getMapping(): array
    {
        return [
            'sslVerifyPeer' => 'verify_peer',
            'sslVerifyPeerName' => 'verify_peer_name',
            'sslAllowSelfSigned' => 'allow_self_signed',
            'sslCafile' => 'cafile',
        ];
    }
}
