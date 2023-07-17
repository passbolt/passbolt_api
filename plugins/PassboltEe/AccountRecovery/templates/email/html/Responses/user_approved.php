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
$authenticationToken = $body['authenticationToken'];

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($user['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $user,
        'datetime' => $created,
        'text' => __(
            '{0}({1}) has approved your recovery request.',
            Purifier::clean($admin['profile']['first_name']),
            Purifier::clean($admin['username']),
        )
    ])
]);

$text = '<h3>' . $title . '</h3><br/>';
$text .= __('Your organization recovery contacts have reviewed and approved your account recovery request.');
$text .= __('Please click on the link below to continue.');
$text .= '<br/><b>' . __('Important')  . ': ' . __('Please use the same computer and browser that you used to initiate the request.') . '</b>';

echo $this->element('Email/module/text', [
    'text' => $text
]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/account-recovery/continue/' . $user['id'] . '/' . $authenticationToken['token'], true),
    'text' => __('Complete account recovery')
]);
