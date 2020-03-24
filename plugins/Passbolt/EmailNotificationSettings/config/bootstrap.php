<?php
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
 * @since         2.10.0
 */
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Passbolt\EmailNotificationSettings\Utility\NotificationSettings\CommentNotificationSettingsDefinition;
use Passbolt\EmailNotificationSettings\Utility\NotificationSettings\GroupNotificationSettingsDefinition;
use Passbolt\EmailNotificationSettings\Utility\NotificationSettings\PurifyNotificationSettingsDefinition;
use Passbolt\EmailNotificationSettings\Utility\NotificationSettings\ResourceNotificationSettingsDefinition;
use Passbolt\EmailNotificationSettings\Utility\NotificationSettings\UserNotificationSettingsDefinition;

Configure::load('Passbolt/EmailNotificationSettings.config', 'default', true);

EventManager::instance()
    ->on(new CommentNotificationSettingsDefinition())
    ->on(new GroupNotificationSettingsDefinition())
    ->on(new PurifyNotificationSettingsDefinition())
    ->on(new ResourceNotificationSettingsDefinition())
    ->on(new UserNotificationSettingsDefinition());
