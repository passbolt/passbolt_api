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
 * @since         3.10.0
 *
 * @see \Passbolt\MfaPolicies\Notification\Email\MfaPoliciesSettingsUpdatedEmailRedactor
 * @var \App\View\AppView $this
 * @var array $body
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
/** @var array $mfaPolicySettings */
$mfaPolicySettings = $body['settings'];
/** @var string $userAgent */
$userAgent = $body['user_agent'];
/** @var string $clientIp */
$clientIp = $body['ip'];

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($operator['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $operator,
        'datetime' => $mfaPolicySettings['modified'],
        'text' => $operator['id'] === $recipient['id'] ?
            __('You edited the MFA policy') :
            __('{0} edited the MFA policy', Purifier::clean($operator['profile']['first_name'])),
    ]),
]);

$rememberMe = __('Disabled');
if ($mfaPolicySettings['remember_me_for_a_month']) {
    $rememberMe = __('Enabled');
}

$text = __('The MFA policy has been updated, the new configuration is as follow:') . '<br/>';
$text .= __('Policy: {0}', Purifier::clean(ucfirst($mfaPolicySettings['policy']))) . '<br/>';
$text .= __('Remember me for a month: {0}', $rememberMe) . '<br/>';

echo $this->element('Email/module/text', [
    'text' => $text,
]);

echo $this->element('Email/module/user_info', compact('userAgent', 'clientIp'));

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/administration/mfa-policy', true),
    'text' => __('view it in passbolt'),
]);
