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
namespace Passbolt\Scim\Service;

use Cake\Core\Configure;
use Cake\I18n\Date;

class ScimTokenExpiryChecker
{
    public const CONFIG_ENFORCE = 'passbolt.plugins.scim.security.secretToken.enforceExpiry';
    public const CONFIG_WARNING_WINDOW = 'passbolt.plugins.scim.security.secretToken.warningWindow';
    public const DEFAULT_WARNING_WINDOW = '14 days';

    /**
     * Return true if the SCIM secret token is expired.
     *
     * @param array $scimConfig SCIM settings.
     * @return bool
     */
    public function isExpired(array $scimConfig): bool
    {
        $expiryDate = $this->getExpiryDate($scimConfig);
        if ($expiryDate === null) {
            return false;
        }

        return Date::now()->greaterThan($expiryDate);
    }

    /**
     * Return true if the SCIM secret token expires within the warning window.
     *
     * @param array $scimConfig SCIM settings.
     * @return bool
     */
    public function isNearExpiry(array $scimConfig): bool
    {
        $expiryDate = $this->getExpiryDate($scimConfig);
        if ($expiryDate === null) {
            return false;
        }

        return $expiryDate->isWithinNext('+' . $this->getWarningWindow());
    }

    /**
     * @return bool
     */
    public function isEnforced(): bool
    {
        return (bool)Configure::read(self::CONFIG_ENFORCE, false);
    }

    /**
     * Return the near-expiry warning window as a string.
     *
     * @return string
     */
    private function getWarningWindow(): string
    {
        $window = Configure::read(self::CONFIG_WARNING_WINDOW);
        if (!is_string($window) || $window === '') {
            return self::DEFAULT_WARNING_WINDOW;
        }

        return $window;
    }

    /**
     * Read the expiry date from the given settings.
     *
     * @param array $scimConfig SCIM settings.
     * @return \Cake\I18n\Date|null
     */
    private function getExpiryDate(array $scimConfig): ?Date
    {
        $expired = $scimConfig['expired'] ?? null;
        if (!is_string($expired) || $expired === '') {
            return null;
        }

        return new Date($expired);
    }
}
