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
 * @see \App\Notification\Email\Redactor\Role\RoleCreatedAdminEmailRedactor
 * @var \App\View\AppView $this
 * @var array $body
 */

use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\Routing\Router;

if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
/** @var array $operator */
$operator = $body['operator'];
/** @var array $role */
$role = $body['role'];
$roleName = Purifier::clean($role['name']);
$operatorFullName = Purifier::clean($operator['profile']['full_name']);

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($operator['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $operator,
        'datetime' => $role['created'],
        'text' => __('{0} created a new role {1}', $operatorFullName, $roleName),
    ]),
]);

$text = __('A new role {0} has been created.', $roleName);

echo $this->element('Email/module/text', ['text' => $text]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/', true),
    'text' => __('Log in passbolt'),
]);
