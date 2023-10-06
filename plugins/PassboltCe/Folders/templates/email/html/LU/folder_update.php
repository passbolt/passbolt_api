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
 * @since         2.13.0
 * @var \App\View\AppView $this
 * @var array $body
 * @var bool $isOperator
 * @var string $userFirstName
 */
use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\Routing\Router;

if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
$user = $body['user'];
$folder = $body['folder'];
$isOperator = $body['isOperator'];
$userFirstName = $body['userFirstName'];
$avatarText = $isOperator ? __('You edited a folder') : __('{0} edited a folder', $userFirstName);

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($user['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $user,
        'datetime' => $folder['modified'],
        'text' => $avatarText,
    ]),
]);

$text = __('Name: {0}', Purifier::clean($folder['name'])) . '<br/>';

echo $this->element('Email/module/text', [
    'text' => $text,
]);
echo $this->element('Email/module/button', [
    'url' => Router::url("/app/folders/view/{$folder['id']}", true),
    'text' => __('View it in passbolt'),
]);
