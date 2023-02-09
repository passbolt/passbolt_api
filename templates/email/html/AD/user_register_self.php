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
 * @since         3.10.0
 */
use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\Routing\Router;
if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
$user = $body['user'];
$recipient = $body['recipient'];
$userFirstName = Purifier::clean($user['profile']['first_name']);
$userFullName = $userFirstName . ' ' . Purifier::clean($user['profile']['last_name']);

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($recipient['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $user,
        'datetime' => $user['created'],
        'text' => __('{0} just created an account on passbolt!', $userFirstName)
    ])
]);

$text = '<h3>' . __('Welcome to {0}!', $userFirstName) . '</h3><br/>';
$text .= __('{0} used the self registration feature to create an account on passbolt.', $userFullName);

echo $this->element('Email/module/text', [
    'text' => $text
]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/users/view/' . $user['id'] , true),
    'text' => __('View user in passbolt')
]);
