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
namespace Passbolt\Scim\Service\Cache;

class ScimTokenExpiredNotificationCacheService extends AbstractScimCacheService
{
    /**
     * @inheritDoc
     */
    public static function getConfigKey(): string
    {
        return 'scimTokenExpiredNotification';
    }

    /**
     * @inheritDoc
     */
    public static function getPerformedKey(): string
    {
        return 'token_expired_notification_performed';
    }

    /**
     * @inheritDoc
     */
    public static function getConfigOptions(): array
    {
        return [
            'duration' => '+1 day',
            'path' => CACHE . 'scim' . DS . 'token_expired_notification' . DS,
        ];
    }
}
