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
 * @since         4.10.0
 */
use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\I18n\DateTime;
use Cake\Routing\Router;
if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
$owner = $body['owner'];
$resource = $body['resource'];
$secret = $body['secret'];
$showSecret = $body['showSecret'];

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($owner['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $owner,
        'datetime' => \Cake\I18n\DateTime::now(),
        'text' => __('{0} shared a resource with you', Purifier::clean($owner['profile']['first_name'])),
    ])
]);

$text = __('{0} shared a resource with you.', Purifier::clean($owner['profile']['first_name'])) . '<br/>';

if ($showSecret) {
    echo $this->element('Email/module/code', ['text' => Purifier::clean($secret)]);
}

echo $this->element('Email/module/button', [
    'url' => Router::url("/app/passwords/view/{$resource['id']}", true),
    'text' => __('View it in passbolt'),
]);
