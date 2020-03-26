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
 * @since         2.14.0
 */
use App\Utility\Purifier;
use Cake\I18n\FrozenTime;
use Cake\Routing\Router;
if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
$owner = $body['owner'];
$count = $body['count'];

echo $this->element('Email/module/avatar',[
    'url' => Router::url(DS . $owner->profile->avatar->url['small'], true),
    'text' => $this->element('Email/module/avatar_text', [
        'username' => Purifier::clean($owner->username),
        'first_name' => Purifier::clean($owner->profile->first_name),
        'last_name' => Purifier::clean($owner->profile->last_name),
        'datetime' => FrozenTime::now(),
        'text' => __('{0} shared a password with you', null)
    ])
]);

$text = __('More than {0} resources were shared with you, go check them out on Passbolt.', $count) . '<br/>';

echo $this->element('Email/module/text', [
    'text' => $text
]);

echo $this->element('Email/module/button', [
    'url' => Router::url("/app/passwords", true),
    'text' => __('view them in passbolt')
]);
