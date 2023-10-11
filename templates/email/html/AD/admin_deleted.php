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
 * @see \App\Notification\Email\Redactor\User\AdminDeleteEmailRedactor
 * @var \App\View\AppView $this
 * @var array $body
 */

use App\Model\Entity\Role;
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
$userFullName = Purifier::clean($user['profile']['first_name']) . ' ' . Purifier::clean($user['profile']['last_name']);
$operatorFullName = Purifier::clean($operator['profile']['first_name']) . ' ' . Purifier::clean($operator['profile']['last_name']);

$avatarText = __('{0} deleted administrator {1}', $operatorFullName, $userFullName);
if ($recipient['id'] === $operator['id']) {
    $avatarText = __('You deleted administrator {0}', $userFullName);
} elseif ($recipient['id'] === $user['id']) {
    $avatarText = __('You have been deleted');
}
echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($operator['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $operator,
        'datetime' => $user['modified'],
        'text' => $avatarText,
    ]),
]);

if ($recipient['id'] !== $user['id']) {
    $text = __(
        'The user {0} ({1}) is now deleted from your organisation in passbolt by {2}.',
        $userFullName,
        Purifier::clean($user['username']),
        $operatorFullName
    );
} else {
    $text = __('{0} deleted you from the passbolt organisation.', $operatorFullName);
}

echo $this->element('Email/module/text', ['text' => $text]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/', true),
    'text' => __('Log in passbolt'),
]);
