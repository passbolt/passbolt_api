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
 * @since         4.9.0
 */

namespace Passbolt\Sso\Service\Cache;

class AzureSsoSecretExpiryNotifyCacheService extends AbstractSsoCacheService
{
    /**
     * Cache configuration key.
     *
     * @return string
     */
    public static function getConfigKey(): string
    {
        return 'azureSecretExpiryNotification';
    }

    /**
     * Return cache configuration to modify (i.e. duration) on top of default cache config.
     *
     * @psalm-suppress UndefinedConstant
     * @return array
     */
    public static function getConfigOptions(): array
    {
        return [
            'duration' => '+1 day',
            'path' => CACHE . 'sso' . DS . 'azure_secret_expiry_notification' . DS,
        ];
    }
}
