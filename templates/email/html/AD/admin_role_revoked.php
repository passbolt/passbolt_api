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
 * @since         4.4.0
 *
 * @see \App\Notification\Email\Redactor\User\UserAdminRoleRevokedEmailRedactor
 * @var \App\View\AppView $this
 * @var array $body
 * @var string $title
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
/** @var array $user */
$user = $body['user'];
/** @var string $userAgent */
$userAgent = $body['user_agent'];
/** @var string $clientIp */
$clientIp = $body['ip'];
$userFullName = Purifier::clean($user['profile']['first_name'] . ' ' . $user['profile']['last_name']);
$operatorFullName = Purifier::clean($operator['profile']['first_name'] . ' ' . $operator['profile']['last_name']);
$roleChanged = Purifier::clean($user['role']['name']);

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($operator['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $operator,
        'datetime' => $user['modified'],
        'text' => Purifier::clean($title),
    ]),
]);

$text = __('{0} changed the role of {1} to {2}.', $operatorFullName, $userFullName, $roleChanged);
$text .= ' ';
$text .= __('{0} can no longer perform administration tasks.', $userFullName);

echo $this->element('Email/module/text', ['text' => $text]);

echo $this->element('Email/module/user_info', compact('userAgent', 'clientIp'));

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/users/view/' . $user['id'], true),
    'text' => __('View it in passbolt'),
]);
