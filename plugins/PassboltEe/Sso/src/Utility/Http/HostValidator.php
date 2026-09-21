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

namespace Passbolt\Sso\Utility\Http;

class HostValidator
{
    /**
     * Remove the brackets around the IPv6.
     *
     * @param string $value IPv6 value.
     * @return string
     */
    public static function stripIpv6Brackets(string $value): string
    {
        if (str_starts_with($value, '[') && str_ends_with($value, ']')) {
            return substr($value, 1, -1);
        }

        return $value;
    }

    /**
     * Check if the IP address is in standard notation.
     *
     * @param string $ip IP value to check.
     * @return bool
     */
    public static function isIpLiteral(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Check if given host is matching the allowed host name pattern.
     *
     * @param string $host Host to check.
     * @return bool
     */
    public static function isHostName(string $host): bool
    {
        /**
         * Dot separated characters. Allowed letters, digits, hyphens and underscores only.
         * Value starting with hyphen is not allowed.
         * Last part should start with a letter, it filters decimal, octal, hexadecimal and short IPv4.
         */
        $pattern = '/^(?=.{1,253}$)(?:[a-z0-9_](?:[a-z0-9_-]{0,61}[a-z0-9_])?\.)*[a-z](?:[a-z0-9_-]{0,61}[a-z0-9_])?$/i'; // phpcs:ignore

        return (bool)preg_match($pattern, $host);
    }

    /**
     * @param string $host Host/IP to check.
     * @return bool
     */
    public static function isSupported(string $host): bool
    {
        return self::isIpLiteral($host) || self::isHostName($host);
    }
}
