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
 * @since         4.0.0
 *
 * @see \Passbolt\Sso\Notification\Email\SsoSettings\SsoSettingsActiveDeletedEmailRedactor
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
/** @var array $operator */
$ssoSetting = $body['ssoSetting'];
/** @var string $userAgent */
$userAgent = $body['user_agent'];
/** @var string $clientIp */
$clientIp = $body['ip'];

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($operator['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $operator,
        'datetime' => $ssoSetting['modified'],
        'text' => $title,
    ]),
]);

$text = __(
    'The SSO provider {0} has been successfully tested & activated by {1}. ',
    ucfirst($ssoSetting['provider']),
    Purifier::clean($operator['profile']['first_name'])
);
$text .= __('All users can now use the Single Sign-on to perform authentication.');

echo $this->element('Email/module/text', [
    'text' => $text,
]);

echo $this->element('Email/module/user_info', compact('userAgent', 'clientIp'));

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/administration/sso', true),
    'text' => __('view it in passbolt'),
]);
