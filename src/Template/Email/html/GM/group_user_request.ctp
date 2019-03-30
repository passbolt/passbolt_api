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
use Cake\I18n\FrozenTime;
use Cake\Routing\Router;
if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
$admin = $body['admin'];
$group = $body['group'];
$groupUsers = $body['groupUsers'];

echo $this->element('Email/module/avatar',[
    'url' => Router::url(DS . $admin->profile->avatar->url['small'], true),
    'text' => $this->element('Email/module/avatar_text', [
        'username' => Purifier::clean($admin->username),
        'first_name' => Purifier::clean($admin->profile->first_name),
        'last_name' => Purifier::clean($admin->profile->last_name),
        'datetime' => FrozenTime::now(),
        'text' => __('{0} requested you to add members to a group', null)
    ])
]);

$text = __('Group: {0}', Purifier::clean($group->name)) . '<br><br>';
$text .= ' ' . __('The following members should be added:') . '<br>';

$text .= $this->element('Email/content/group_users_summary', [
    'groupUsers' => $groupUsers
]);

echo $this->element('Email/module/text', [
    'text' => $text
]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/groups/edit/'. $group->id, true),
    'text' => __('Edit group members')
]);
