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
 * @since         3.6.0
 */

use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\Routing\Router;
if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
$user = $body['user'];
$admin = $body['admin'];
$created = $body['created'];
$status = $body['status'];
$userFirstName = Purifier::clean($user['profile']['first_name']);
$userEmail = Purifier::clean($user['username']);

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($admin['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $admin,
        'datetime' => $created,
        'text' => __(
            'You have updated a recovery request to {0}.',
            $status
        )
    ])
]);

$text = '<h3>' . $title . '</h3><br/>';
$text .= __('You have set the status of the account recovery request initiated by {0} ({1}) to {2}.', $userFirstName, $userEmail, $status);

echo $this->element('Email/module/text', [
    'text' => $text
]);