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
$user = $body['user'];
$resource = $body['resource'];
$showUsername = $body['showUsername'];
$showUri = $body['showUri'];
$showDescription = $body['showDescription'];
$showSecret = $body['showSecret'];

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($user['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $user,
        'datetime' => $resource['created'],
        'text' => __('You have saved a new password')
    ])
]);

$text = __('Name: {0}', Purifier::clean($resource['name'])) . '<br/>';

if ($showUsername) {
    $text .= __('Username: {0}', Purifier::clean($resource['username'])) . '<br/>';
}
if ($showUri) {
    $text .= __('URL: {0}', Purifier::clean($resource['uri'])) . '<br/>';
}
if ($showDescription && isset($resource['description'])) {
    $text .= __('Description: {0}', Purifier::clean($resource['description'])) . '<br/>';
}
echo $this->element('Email/module/text', [
    'text' => $text
]);
if ($showSecret) {
    echo $this->element('Email/module/code', [
        'text' => Purifier::clean($resource['secrets'][0]['data'])
    ]);
}
echo $this->element('Email/module/button', [
    'url' => Router::url("/app/passwords/view/{$resource['id']}", true),
    'text' => __('View it in passbolt')
]);
