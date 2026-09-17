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
 * @since         5.16.0
 *
 * @see \Passbolt\OfflineMode\Notification\Email\OfflineSettingsSetEmailRedactor
 * @var \App\View\AppView $this
 * @var array $body
 * @var string $title
 */

use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\Routing\Router;

if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
/** @var array $recipient */
$recipient = $body['recipient'];
/** @var array $operator */
$operator = $body['operator'];
/** @var array $offlineSettings */
$offlineSettings = $body['settings'];
/** @var string $userAgent */
$userAgent = $body['user_agent'];
/** @var string $clientIp */
$clientIp = $body['ip'];

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($operator['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $operator,
        'datetime' => $offlineSettings['modified'],
        'text' => Purifier::clean($title),
    ]),
]);

$text = __('The Offline Mode settings have been updated, the new configuration is as follows:') . '<br/>';
$text .= __('Maximum session duration: {0} seconds', (int)$offlineSettings['max_session_duration']) . '<br/>';
$text .= __('Data retention period: {0} days', (int)$offlineSettings['data_retention_period']) . '<br/>';
$text .= __('Maximum number of offline items (per user): {0}', (int)$offlineSettings['max_items']) . '<br/>';

echo $this->element('Email/module/text', [
    'text' => $text,
]);

echo $this->element('Email/module/user_info', compact('userAgent', 'clientIp'));

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/administration/offline-mode', true),
    'text' => __('View it in passbolt'),
]);
