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
    use Cake\Routing\Router;

    $user = $body['user'];
    $token = $body['token'];

    $text = $this->element('email/content/register_avatar_text', ['user' => $user]);

    echo $this->element('email/module/avatar',[
        'url' => Router::url('/img/avatar' . DS . 'user.png', true),
        'text' => $text
    ]);

    echo $this->element('email/module/text', [
        'text' => $this->element('email/content/register_text', ['user' => $user])
    ]);

    echo $this->element('email/module/button', [
        'url' => Router::url('/setup/install/' . $user['id'] . '/' . $token['token']),
        'text' => __('get started')
    ]);
?>
