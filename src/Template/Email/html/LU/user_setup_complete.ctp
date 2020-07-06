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
 * @see           AdminUserSetupCompleteEmailRedactor
 */

use App\Model\Entity\User;
use App\Notification\Email\Redactor\AdminUserSetupCompleteEmailRedactor;
use App\Utility\Purifier;
use Cake\Routing\Router;

if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
/** @var User $user */
$user = $body['user'];
/** @var User $user */
$admin = $body['admin'];
/** @var User $invitedBy */
$invitedBy = $body['invitedBy'];
/** @var string $invitedWhen */
$invitedWhen = $body['invitedWhen'];
/** @var bool $invitedByYou */
$invitedByYou = $body['invitedByYou'];

$avatar = 'img/avatar/user.png';

echo $this->element('Email/module/avatar',[
    'url' => Router::url($avatar, true),
    'text' => $this->element('Email/module/avatar_text', [
        'username' => Purifier::clean($user->username),
        'first_name' => Purifier::clean($user->profile->first_name),
        'last_name' => Purifier::clean($user->profile->last_name),
        'datetime' => $user->modified,
        'text' => __(
            '{0} have just activated their account on passbolt!',
            $user->profile->first_name
        )
    ])
]);

$text = ' ' . __('The user is now active on passbolt and you can share passwords with them.');
$text .= ' ';
if ($invitedByYou) {
    $text .= __('This user was invited by you {0}.', $invitedWhen);
} else if ($user->username === $invitedBy->username) {
    $text .= __('This user signed up themselves, since you have open registration active.');
} else {
    $text .= __('This user was invited by {0} {1}.', $invitedBy->profile->first_name, $invitedWhen);
}
$text .= '<br/>';
echo $this->element('Email/module/text', [
    'text' => $text
]);

echo $this->element('Email/module/button', [
    'url' => Router::url("/app/users", true),
    'text' => __('view users')
]);
