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
 * @since         5.14.0
 * @var \App\View\AppView $this
 * @var array $body
 */

use Cake\I18n\DateTime;
use Cake\Routing\Router;
use App\View\Helper\AvatarHelper;

if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl'] ?? Router::fullBaseUrl());
}
$user = $body['user'];

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($user['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $user,
        'datetime' => DateTime::now(),
        'text' => __('A security key was removed from your account.'),
    ]),
]);

$text = __('A security key was removed from your multi-factor authentication settings.');
$text .= ' ' . __('If you did not perform this action, please contact your administrator immediately.');

echo $this->element('Email/module/text', [
    'text' => $text,
]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/', true),
    'text' => __('Log in passbolt'),
]);
