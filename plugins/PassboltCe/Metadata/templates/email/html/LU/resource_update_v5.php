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
 *
 * @var array $body
 * @var string $title
 */
use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\Routing\Router;

if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
$user = $body['user'];
$recipient = $body['recipient'];
$resource = $body['resource'];
$armoredSecret = $body['armoredSecret'];
$showSecret = $body['showSecret'];

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($user['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $user,
        'datetime' => $resource['modified'],
        'text' => Purifier::clean($title),
    ])
]);

if ($user['id'] === $recipient['id']) {
    $text = __('You have edited a resource.') . '<br/>';
} else {
    $text = __('{0} have edited a resource.', Purifier::clean($user['profile']['first_name'])) . '<br/>';
}

echo $this->element('Email/module/text', [
    'text' => $text,
]);
if ($showSecret && $armoredSecret !== null) {
    echo $this->element('Email/module/code', ['text' => Purifier::clean($armoredSecret)]);
}
echo $this->element('Email/module/button', [
    'url' => Router::url("/app/passwords/view/{$resource['id']}", true),
    'text' => __('View it in passbolt')
]);
