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
 * @since         4.3.0
 */
use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\Routing\Router;
if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
$user = $body['user'];
$userFullName = Purifier::clean($body['userFullName']);

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($user['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $user,
        'datetime' => $user['modified'],
        'text' => $title,
    ])
]);

$text = '<h3>' . __('User suspended') . '</h3><br/>';
$text .= __('The user {0} has been suspended.', $userFullName) . ' ';
$text .= __('This user will not be able to sign in to passbolt and receive email notifications.') . ' ';
$text .= __('Other users can share resources and add this user to a group.');

echo $this->element('Email/module/text', [
    'text' => $text
]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/users/view/' . $user['id'] , true),
    'text' => __('Check user suspension')
]);
