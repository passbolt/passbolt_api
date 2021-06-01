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
$admin = $body['admin'];
$group = $body['group'];
$count = $body['count'];

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($admin->profile->avatar),
    'text' => $this->element('Email/module/avatar_text', [
        'username' => Purifier::clean($admin->username),
        'first_name' => Purifier::clean($admin->profile->first_name),
        'last_name' => Purifier::clean($admin->profile->last_name),
        'datetime' => $group->modified,
        'text' => __('{0} deleted several group', Purifier::clean($admin->profile->first_name))
    ])
]);

$text = __('{0} deleted {1} groups you were a member of.', Purifier::clean($admin->profile->first_name), $count) . ' ';
$text .= __('All passwords that were shared only with this group were also deleted.') . ' ';
$text .= __('It would be too much to list them here, but you can get more information on passbolt.') ;

echo $this->element('Email/module/text', [
    'text' => $text
]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/', true),
    'text' => __('log in passbolt')
]);
