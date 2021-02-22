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

/**
 * Interface used by any source of notification settings from where settings can be read.
 *
 * @package Passbolt\EmailNotificationSettings\Utility\NotificationSettingsSource
 */
interface ReadableEmailNotificationSettingsSourceInterface
{
    /**
     * Return an array of notification settings with notification setting name as key and notification setting value as value.
     * Notification setting names must use the dotted key notation.
     *
     * @return array
     */
    public function read();
}
