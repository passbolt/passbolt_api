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
use Cake\I18n\FrozenTime;
use Cake\Routing\Router;
if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
$admin = $body['admin'];
$user = $body['user'];
$groups = $body['groups'];

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($admin['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $admin,
        'datetime' => FrozenTime::now(),
        'text' => __('{0} deleted the user {1}', null, Purifier::clean($user['profile']['first_name']))
    ])
]);

$text = __('The user {0} {1} ({2}) is now deleted from your organisation in passbolt.',
    Purifier::clean($user['profile']['first_name']),
    Purifier::clean($user['profile']['last_name']),
    Purifier::clean($user['username'])
);
$text .= ' ' . __('This user was a member of the following group(s) you manage:') . '<br>';

$text .= $this->element('Email/content/user_delete_groups_summary', [
    'groups' => $groups
]);

echo $this->element('Email/module/text', [
    'text' => $text
]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/', true),
    'text' => __('log in passbolt')
]);
