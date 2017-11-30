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
use Cake\Core\Configure;
use Cake\Routing\Router;

$user = $body['user'];
$resource = $body['resource'];
echo $this->element('email/module/avatar',[
    // @TODO avatar url in email
    'url' => Router::url('/img/avatar' . DS . 'user.png', true),
    'text' => $this->element('email/module/avatar_text', [
        'username' => $user->username,
        'first_name' => 'You',
        'last_name' => '',
        'datetime' => $resource->created,
        'text' => __('You have saved a new password')
    ])
]);

$text = __('Name: {0}', $resource->name) . '<br/>';

if (Configure::read('passbolt.email.show.username')) {
    $text .= __('Username: {0}', $resource->username) . '<br/>';
}
if (Configure::read('passbolt.email.show.uri')) {
    $text .= __('URL: {0}', $resource->uri) . '<br/>';
}
if (Configure::read('passbolt.email.show.description')) {
    $text .= __('URL: {0}', $resource->description) . '<br/>';
}
echo $this->element('email/module/text', [
    'text' => $text
]);
if (Configure::read('passbolt.email.show.secret')) {
    echo $this->element('email/module/code', [
        'text' => $resource->secrets[0]->data
    ]);
}
echo $this->element('email/module/button', [
    'url' => Router::url('/'),
    'text' => __('log in passbolt')
]);
