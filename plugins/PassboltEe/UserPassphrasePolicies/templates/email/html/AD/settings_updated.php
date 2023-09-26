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
 * @since         4.3.0
 *
 * @see \Passbolt\UserPassphrasePolicies\Notification\Email\UserPassphrasePoliciesSettingsUpdatedEmailRedactor
 * @var \App\View\AppView $this
 * @var array $body
 */

use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\Routing\Router;

if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
/** @var array $recipient */
$recipient = $body['recipient'];
/** @var array $operator */
$operator = $body['operator'];
/** @var array $userPassphrasePoliciesSettings */
$userPassphrasePoliciesSettings = $body['settings'];
/** @var string $userAgent */
$userAgent = $body['user_agent'];
/** @var string $clientIp */
$clientIp = $body['ip'];
/** @var string $operatorFullName */
$operatorFullName = Purifier::clean($operator['profile']['first_name']) . ' ' . Purifier::clean($operator['profile']['last_name']);

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($operator['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $operator,
        'datetime' => $userPassphrasePoliciesSettings['modified'],
        'text' => $operator['id'] === $recipient['id'] ?
            __('You edited the user passphrase policy') :
            __('{0} edited the user passphrase policy', $operatorFullName),
    ]),
]);

$text = __(
    'The user passphrase policy settings has been updated by {0}.',
    $operator['id'] === $recipient['id'] ? __('you') : $operatorFullName
);
$text .= '<br/>';

echo $this->element('Email/module/text', ['text' => $text]);

echo $this->element('Email/module/user_info', compact('userAgent', 'clientIp'));

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/administration/user-passphrase-policy', true),
    'text' => __('view it in passbolt'),
]);
