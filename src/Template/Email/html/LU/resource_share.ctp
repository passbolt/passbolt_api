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
$owner = $body['owner'];
$resource = $body['resource'];
$secret = $body['secret'];
$showUsername = $body['showUsername'];
$showUri = $body['showUri'];
$showDescription = $body['showDescription'];
$showSecret = $body['showSecret'];

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

$text = __('Name: {0}', Purifier::clean($resource->name)) . '<br/>';

if ($showUsername) {
    $text .= __('Username: {0}', Purifier::clean($resource->username)) . '<br/>';
}
if ($showUri) {
    $text .= __('URL: {0}', Purifier::clean($resource->uri)) . '<br/>';
}
if ($showDescription) {
    $text .= __('Description: {0}', Purifier::clean($resource->description)) . '<br/>';
}
echo $this->element('Email/module/text', [
    'text' => $text
]);
if ($showSecret) {
    echo $this->element('Email/module/code', [
        'text' => $secret
    ]);
}
echo $this->element('Email/module/button', [
    'url' => Router::url("/app/passwords/view/{$resource->id}", true),
    'text' => __('view it in passbolt')
]);
