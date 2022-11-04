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
     * @return string
     */
    public function sanitizeRedirect(): string
    {
        $redirectUrl = $this->_extractFirstParameter('redirect');
        $redirectLoopBlocker = $this->getController()->getRequest()->getPath();
        $sanitizedRedirectUrl = $this->sanitize($redirectUrl, [$redirectLoopBlocker]);

        return $sanitizedRedirectUrl;
    }

    /**
     * Sanitize URL
     *
     * @param string $url URL to sanitize
     * @param array $blacklist Array of URL parts that are not allowed to be in the sanitized URL
     * @param bool $ensureStartsWithSlash Whether or not to force the URL to start with a '/' character
     * @param bool $escapeSpecialChars Whether or not to escape special characters
     * @return string
     */
    public function sanitize(
        string $url,
        array $blacklist = [],
        bool $ensureStartsWithSlash = true,
        bool $escapeSpecialChars = true
    ): string {
        if (empty($url)) {
            return '';
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
        $request = $this->getController()->getRequest();
        $queryValue = $request->getQuery($queryKey);
        if (is_array($queryValue)) {
            $queryValue = $queryValue[0] ?? '';
        }

        return $queryValue ?? '';
    }
}
