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
 * @see \Passbolt\Sso\Notification\Email\Stage2\AzureSsoProviderErrorRedactor
 * @var \App\View\AppView $this
 * @var array $body
 * @var string $title
 * @var string $error
 * @var string $errorDescription
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
/** @var string $error */
$error = Purifier::clean($body['error']);
/** @var string $errorDescription */
$errorDescription = Purifier::clean($body['error_description']);

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($recipient['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $recipient,
        'datetime' => FrozenTime::now(),
        'text' => $title,
    ]),
]);

$text = __('Users are unable to log in via Azure SSO.');
$text .= ' ';
$text .= __('This can happen when the client secret of Azure AD is expired, or the SSO configurations (client credentials) are invalid.');
$text .= '<br/><br/>';
$text .= __('See Azure error response below:') . '<br/>';
$text .= __('Error: {0}', $error) . '<br/>';
$text .= __('Error description: {0}', $errorDescription) . '<br/>';
$text .= '<br/>';
$text .= __('To fix this, you as an administrator have to update the Azure SSO credentials.');

echo $this->element('Email/module/text', ['text' => $text]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/administration/sso', true),
    'text' => __('Update it in passbolt'),
]);
