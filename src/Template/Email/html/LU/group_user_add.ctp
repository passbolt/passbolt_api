<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
use App\Utility\Purifier;
use Cake\Routing\Router;

$admin = $body['admin'];
$group_user = $body['group_user'];
$group = $body['group'];

echo $this->element('email/module/avatar',[
    'url' => Router::url($admin->profile->avatar->url['small'], true),
    'text' => $this->element('email/module/avatar_text', [
        'username' => Purifier::clean($admin->username),
        'first_name' => Purifier::clean($admin->profile->first_name),
        'last_name' => Purifier::clean($admin->profile->last_name),
        'datetime' => $group_user->created,
        'text' => __('{0} added you to the group {1}', null, Purifier::clean($group->name))
    ])
]);

$text = __('As member of the group you now have access to all the passwords that are shared with this group.');
if ($group_user->is_admin) {
    $text .= ' ' . __('And as group manager you are also authorized to edit the members of the group.');
}
echo $this->element('email/module/text', [
    'text' => $text
]);

echo $this->element('email/module/button', [
    'url' => Router::url('/'),
    'text' => __('log in passbolt')
]);
