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
 * @since         3.3.0
 */
use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\I18n\FrozenTime;

$user = $body['user'];
$ip = $body['ip'];
$message = $body['message'];

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($user['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $user,
        'datetime' => FrozenTime::now(),
        'text' => __('Security warning!')
    ])
]);

$text = '<h3>' . __('Security warning!') . '</h3><br/>';
$text = '<h4>' . $message . '</h4><br/>';
$text .= __('An unknown user with IP: {0}  attempted to steal your login data.', $ip);
$text .= ' ' . __('Please get in touch with one of your administrators.');
echo $this->element('Email/module/text', [
    'text' => $text
]);
