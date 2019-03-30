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
use Cake\Routing\Router;
if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
$user = $body['user'];
$admin = $body['admin'];
$token = $body['token'];

echo $this->element('Email/module/avatar',[
    'url' => Router::url(DS . $admin->profile->avatar->url['small'], true),
    'text' => $this->element('Email/module/avatar_text', [
        'username' => Purifier::clean($admin->username),
        'first_name' => Purifier::clean($admin->profile->first_name),
        'last_name' => Purifier::clean($admin->profile->last_name),
        'datetime' => $user->created,
        'text' => __('{0} just created an account for you on passbolt!', Purifier::clean($admin->profile->first_name))
    ])
]);

$text = '<h3>' . __('Welcome {0}', Purifier::clean($user->profile->first_name)) . ',</h3><br/>';
$text .= __('{0} just invited you to join passbolt at {1}',
        ucfirst(Purifier::clean($admin->profile->first_name)),
        '<a href="' . Router::url('/',true) . '">' . Router::url('/',true) . '</a>'
        );
$text .= ' ' . __('Passbolt is an open source password manager.');
$text .= ' ' . __('It is designed to allow sharing credentials securely with your team!');
$text .= '<br/><br/>';
$text .= __('Let\'s take the next five minutes to get you started!');
$text .= '<br/>';
echo $this->element('Email/module/text', [
    'text' => $text
]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/setup/install/' . $user['id'] . '/' . $token['token'], true),
    'text' => __('get started')
]);
