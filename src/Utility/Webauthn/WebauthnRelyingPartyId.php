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
 * @since         5.17.0
 */
namespace App\Utility\Webauthn;

use App\Error\Exception\WebauthnRelyingPartyIdInvalidException;
use Cake\Core\Configure;
use Cake\Validation\Validation;

class WebauthnRelyingPartyId
{
    /**
     * @return string
     * @throws \App\Error\Exception\WebauthnRelyingPartyIdInvalidException if the host/domain is invalid
     */
    public static function getOrFail(): string
    {
        $host = parse_url((string)Configure::read('App.fullBaseUrl'), PHP_URL_HOST);
        if (!is_string($host) || empty($host)) {
            throw new WebauthnRelyingPartyIdInvalidException(
                __('Passkeys require a valid full base URL to be configured.'),
            );
        }

        // trim brackets from the IPv6 hosts
        $host = trim($host, '[]');

        // Reject IPs
        if (Validation::ip($host)) {
            throw new WebauthnRelyingPartyIdInvalidException(
                __('Passkeys require Passbolt to be reached through a domain name, not an IP address.'),
            );
        }

        if (filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) === false) {
            throw new WebauthnRelyingPartyIdInvalidException(
                __('Passkeys require Passbolt to be reached through a valid domain name.'),
            );
        }

        return $host;
    }
}
