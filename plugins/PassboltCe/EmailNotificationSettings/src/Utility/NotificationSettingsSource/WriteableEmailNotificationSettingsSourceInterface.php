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
 * @since         2.13.0
 */
namespace Passbolt\EmailNotificationSettings\Utility\NotificationSettingsSource;

use App\Utility\UserAccessControl;

interface WriteableEmailNotificationSettingsSourceInterface
{
    /**
     * Write an array of notification settings into a storage chosen by the implementation.
     *
     * @param array $notificationSettingsData Notification settings with the dotted notation.
     * @param \App\Utility\UserAccessControl $userAccessControl UAC
     * @return void
     */
    public function write(array $notificationSettingsData, UserAccessControl $userAccessControl);
}
