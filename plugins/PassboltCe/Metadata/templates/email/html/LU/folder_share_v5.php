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
 * @since         4.10.0
 * @var \App\View\AppView $this
 * @var array $body
 */
use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\I18n\DateTime;
use Cake\Routing\Router;

if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
$user = $body['user'];
$folder = $body['folder'];

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($user['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $user,
        'datetime' => DateTime::now(),
        'text' => __('{0} shared a folder with you', Purifier::clean($user['profile']['first_name'])),
    ]),
]);

$text = __('{0} has shared a folder with you.', Purifier::clean($user['profile']['first_name'])) . '<br/>';

echo $this->element('Email/module/text', [
    'text' => $text,
]);
echo $this->element('Email/module/button', [
    'url' => Router::url("/app/folders/view/{$folder['id']}", true),
    'text' => __('View it in passbolt'),
]);
