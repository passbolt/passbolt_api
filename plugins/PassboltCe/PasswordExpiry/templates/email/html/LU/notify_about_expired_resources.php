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
 * @since         4.5.0
 *
 * @see \Passbolt\PasswordExpiry\Notification\Email\PasswordExpirySettingsUpdatedEmailRedactor
 * @var \App\View\AppView $this
 * @var array $body
 * @var string $title
 */

use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\I18n\DateTime;
use Cake\Routing\Router;

if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
$message = $body['message'];
$user = $body['user'];

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($user['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $user,
        'text' => __('You have been requested to change them'),
        'datetime' => DateTime::now(),
    ]),
]);
$text = $message . ' ';
$text .= __('Please rotate them to ensure continued security.') . '<br/>';

echo $this->element('Email/module/text', [
    'text' => $text,
]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/passwords/filter/expired', true),
    'text' => __('View them in passbolt'),
]);
