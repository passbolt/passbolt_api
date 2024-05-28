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
 * @since         4.9.0
 *
 * @see \Passbolt\Sso\Notification\Email\Stage2\AzureSsoSecretExpiryNotifyRedactor
 * @var \App\View\AppView $this
 * @var array $body
 * @var string $title
 */

use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\I18n\FrozenTime;
use Cake\Routing\Router;

if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
/** @var array $recipient */
$recipient = $body['recipient'];
/** @var string $secretExpiryDate */
$secretExpiryDate = Purifier::clean($body['secret_expiry_date']);

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($recipient['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $recipient,
        'datetime' => FrozenTime::now(),
        'text' => Purifier::clean($title),
    ]),
]);

$text = __('The client secret expiry date of your Azure AD instance is {0}.', $secretExpiryDate);
$text .= ' ';
$text .= __('Note that passbolt users will not be able to log in via Azure SSO after the client expiry date.');
$text .= ' ';
$text .= __('We advise you to fix this on the Azure side and update the latest details in the passbolt.');

echo $this->element('Email/module/text', ['text' => $text]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/administration/sso', true),
    'text' => __('Update it in passbolt'),
]);
