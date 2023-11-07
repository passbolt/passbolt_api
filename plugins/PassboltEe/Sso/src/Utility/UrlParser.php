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
 * @since         4.4.0
 */
namespace Passbolt\Sso\Utility;

class UrlParser
{
    /**
     * Returns full URL without any query string, fragments, etc.
     *
     * @param string $url URL.
     * @return string
     */
    public static function getUrlOnly(string $url): string
    {
        $result = sprintf(
            '%s://%s%s',
            parse_url($url, PHP_URL_SCHEME),
            parse_url($url, PHP_URL_HOST),
            parse_url($url, PHP_URL_PATH)
        );

        return trim($result, '/');
    }

    /**
     * Returns query string as array from the given URL.
     *
     * Example:
     * - https://passbolt.com?foo=bar&abc=xyz -> ['foo' => 'bar', 'abc' => 'xyz']
     * - https://passbolt.com -> []
     *
     * @param string $url URL.
     * @return array
     */
    public static function parseQueryString(string $url): array
    {
        $queryString = parse_url($url, PHP_URL_QUERY);

        if (!is_string($queryString)) {
            return [];
        }

        $queryStringAsArray = [];
        parse_str($queryString, $queryStringAsArray);

        return $queryStringAsArray;
    }
}
