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
$invitedBy = $body['invitedBy'];
$invitedWhen = $body['invitedWhen'];

echo $this->element('Email/module/avatar',[
    'url' => Router::url(DS . $user->profile->avatar->url['small'], true),
    'text' => $this->element('Email/module/avatar_text', [
        'username' => Purifier::clean($user->username),
        'first_name' => Purifier::clean($user->profile->first_name),
        'last_name' => Purifier::clean($user->profile->last_name),
        'datetime' => $user->modified,
        'text' => __(
            '{0} have just activated their account on',
            $user->profile->first_name
        )
    ])
]);

$text = ' ' . __('The user is now active on Passbolt and you can share passwords with them.');
$text .= '<br/><br/>';
$text .= __('This user was invited by {0} {1}.', $invitedBy, $invitedWhen);
$text .= '<br/>';
echo $this->element('Email/module/text', [
    'text' => $text
]);
