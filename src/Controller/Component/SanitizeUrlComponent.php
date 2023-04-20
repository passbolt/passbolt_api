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
 * @since         3.8.0
 */
namespace App\Controller\Component;

use Cake\Controller\Component;

class SanitizeUrlComponent extends Component
{
    /**
     * Sanitize redirect URL
     *
     * @param string $redirectLoopStop Prevent redirecting if this string is found in the redirect parameter
     * @param bool $allowEmpty Whether or not to allow an empty URL
     * @param bool $ensureStartsWithSlash Whether or not to force the URL to start with a '/' character
     * @param bool $escapeSpecialChars Whether or not to escape special characters
     * @return string
     */
    public function sanitizeRedirect(
        string $redirectLoopStop = '',
        bool $allowEmpty = false,
        bool $ensureStartsWithSlash = true,
        bool $escapeSpecialChars = true
    ): string {
        $redirectUrl = $this->_extractFirstParameter('redirect');
        $loopStop = empty($redirectLoopStop) ? $this->getController()->getRequest()->getPath() : $redirectLoopStop;
        $sanitizedRedirectUrl = $this->sanitize(
            $redirectUrl,
            [$loopStop],
            $allowEmpty,
            $ensureStartsWithSlash,
            $escapeSpecialChars
        );

        return $sanitizedRedirectUrl;
    }

    /**
     * Sanitize URL
     *
     * @param string $url URL to sanitize
     * @param array $blacklist Array of URL parts that are not allowed to be in the sanitized URL
     * @param bool $allowEmpty Whether or not to allow an empty URL
     * @param bool $ensureStartsWithSlash Whether or not to force the URL to start with a '/' character
     * @param bool $escapeSpecialChars Whether or not to escape special characters
     * @return string
     */
    public function sanitize(
        string $url,
        array $blacklist = [],
        bool $allowEmpty = false,
        bool $ensureStartsWithSlash = true,
        bool $escapeSpecialChars = true
    ): string {
        if (empty($url)) {
            return $allowEmpty ? '' : '/';
        }
        if ($ensureStartsWithSlash && substr($url, 0, 1) !== '/') {
            return '/';
        }
        if (str_contains($url, '..')) {
            return '/';
        }
        foreach ($blacklist as &$path) {
            if (str_contains($url, $path)) {
                return '/';
            }
        }
        if ($escapeSpecialChars) {
            return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        }

        return $url;
    }

    /**
     * Get the value from the first matching query key
     *
     * @param string $queryKey The query parameter to extract
     * @return string
     */
    protected function _extractFirstParameter(string $queryKey): string
    {
        $queryValue = $this->getController()->getRequest()->getQuery($queryKey);
        if (is_array($queryValue)) {
            $queryValue = $queryValue[0] ?? '';
        }

        return $queryValue ?? '';
    }
}
