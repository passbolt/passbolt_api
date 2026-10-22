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
 * @since         5.17.0
 *
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
$user = $body['user'];
$userFullName = Purifier::clean($body['userFullName']);

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($user['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $user,
        'datetime' => \Cake\I18n\DateTime::now(),
        'text' => Purifier::clean($title),
    ]),
]);

$text = __('The user {0} has been suspended after too many failed multi-factor authentication attempts.', $userFullName) . ' ';
$text .= __('This user will not be able to sign in to passbolt and receive email notifications.') . ' ';
$text .= __('Review the activity of this account before you restore the access.');

echo $this->element('Email/module/text', [
    'text' => $text,
]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/users/view/' . $user['id'], true),
    'text' => __('View it in passbolt'),
]);
