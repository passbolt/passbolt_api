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
 * @see \Passbolt\Scim\Notification\Email\ScimTokenExpiredEmailRedactor
 * @var \App\View\AppView $this
 * @var array $body
 * @var string $title
 */

use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\I18n\DateTime;
use Cake\Routing\Router;

if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
/** @var array $recipient */
$recipient = $body['recipient'];
/** @var string $tokenExpiryDate */
$tokenExpiryDate = Purifier::clean($body['token_expiry_date']);

echo $this->element('Email/module/avatar', [
    'url' => AvatarHelper::getAvatarUrl($recipient['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $recipient,
        'datetime' => DateTime::now(),
        'text' => Purifier::clean($title),
    ]),
]);

$text = __('The SCIM secret token of your organization expired on {0}.', $tokenExpiryDate);
$text .= ' ';
$text .= __('Your identity provider cannot provision users any more.');
$text .= ' ';
$text .= __('Create a new SCIM secret token, then set the new token in your identity provider.');

echo $this->element('Email/module/text', ['text' => $text]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/administration/user-provisionning/scim', true),
    'text' => __('Rotate the token in passbolt'),
]);
