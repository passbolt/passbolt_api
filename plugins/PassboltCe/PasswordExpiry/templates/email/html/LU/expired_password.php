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
 */

use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\I18n\FrozenTime;
use Cake\Routing\Router;

if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl(),
    'text' => $this->element('Email/module/avatar_anonymous_text', [
        'title' => $title,
        'text' => __('You have been requested to change them'),
        'datetime' => FrozenTime::now(),
    ]),
]);

$text = __('Access for users to your shared passwords have been revoked.') . ' ';
$text .= __('These passwords are now marked as expired.') . ' ';
$text .= __('Please rotate them to ensure continued security.') . '<br/>';

echo $this->element('Email/module/text', [
    'text' => $text,
]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/passwords/filter/expired', true),
    'text' => __('Change them in passbolt'),
]);
