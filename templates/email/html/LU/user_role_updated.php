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
 * @since         5.8.0
 *
 * @var string $title
 */

use App\Notification\Email\Redactor\User\UserAdminRoleRevokedEmailRedactor;
use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\Core\Configure;
use Cake\Routing\Router;

if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
/** @var array $user */
$user = $body['recipient'];
/** @var array $operator */
$operator = $body['operator'];
/** @var string $userAgent */
$userAgent = $body['user_agent'];
/** @var string $clientIp */
$clientIp = $body['ip'];
/** @var bool $isAdminRoleRevoked */
$isAdminRoleRevoked = $body['isAdminRoleRevoked'];

$recipientFullName = Purifier::clean($user['profile']['first_name'] . ' ' . $user['profile']['last_name']);
$operatorFullName = Purifier::clean($operator['profile']['first_name'] . ' ' . $operator['profile']['last_name']);

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($operator['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $operator,
        'datetime' => $user['modified'],
        'text' => Purifier::clean($title),
    ]),
]);

$text = __('{0} changed your role to {1}.', $operatorFullName, Purifier::clean($user['role']['name']));

if (Configure::read(UserAdminRoleRevokedEmailRedactor::CONFIG_KEY_SEND_USER_EMAIL) && $isAdminRoleRevoked) {
    $text .= ' ';
    $text .= __('You can no longer perform administration tasks.');
}

echo $this->element('Email/module/text', ['text' => $text]);

echo $this->element('Email/module/user_info', compact('userAgent', 'clientIp'));

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/users/view/' . $user['id'], true),
    'text' => __('View it in passbolt'),
]);
