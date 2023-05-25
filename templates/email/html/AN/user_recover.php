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
 * @since         2.0.0
 */
use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\Routing\Router;
if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
$user = $body['user'];
$token = $body['token'];
$case = $body['case'];

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($user['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $user,
        'datetime' => $token['created'],
        'text' => __('You have initiated an account recovery!')
    ])
]);

$text = '<h3>' . __('Welcome back!') . '</h3><br/>';
$text .= __('You just requested to recover your passbolt account on this device.');
$text .= ' ' . __('Make sure you have a backup of your secret key handy.');
$text .= ' ' . __('Click on the link below to proceed.');
echo $this->element('Email/module/text', [
    'text' => $text
]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/setup/recover/start/' . $user['id'] . '/' . $token['token'] . '?case=' . $case, true),
    'text' => __('start recovery')
]);
