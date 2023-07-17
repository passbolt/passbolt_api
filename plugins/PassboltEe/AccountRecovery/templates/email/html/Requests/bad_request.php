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
 * @since         3.6.0
 */
use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\Routing\Router;
if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
$user = $body['user'];
$admin = $body['admin'];
$created = $body['created'];
$subject = $body['subject'];
$clientIp = $body['clientIp'];

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($user['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $admin,
        'datetime' => $created,
        'text' => $subject,
    ])
]);

$text = '<h3>' . __('Suspicious recovery request') . '</h3><br/>';
$text .= __('An account recovery request was attempted from a user with client IP {0} for {1}.', Purifier::clean($clientIp), Purifier::clean($user['profile']['first_name']));
$text .= ' ' . __('The request could not be found in the database.');
$text .= ' ' . __('This is a potential security threat.');
echo $this->element('Email/module/text', [
    'text' => $text
]);
