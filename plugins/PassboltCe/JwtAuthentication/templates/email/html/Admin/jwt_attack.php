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
 *
 * @var array $body
 */
use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\I18n\DateTime;

$user = $body['user'];
$clientIp = $body['clientIp'];
$userAgent = $body['userAgent'];
$message = $body['message'];

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($user['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $user,
        'datetime' => DateTime::now(),
        'text' => __('Security warning!')
    ])
]);

$text = '<h3>' . __('Security warning!') . '</h3><br/>';
$text = '<h4>' . $message . '</h4><br/>';
$text .= __('An unknown user attempted to identify as {0}.', $user['username']);
$text .= ' ' . __('This is a potential security issue.');
$text .= ' ' . __('Please investigate!');
echo $this->element('Email/module/text', [
    'text' => $text
]);
echo $this->element('Email/module/user_info', compact('userAgent', 'clientIp'));
