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
 * @since         2.0.0
 */
use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\Routing\Router;
if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
$creator = $body['creator'];
$comment = $body['comment'];
$resource = $body['resource'];
$showComment = $body['showComment'];

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($creator['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $creator,
        'datetime' => $comment['created'],
        'text' => __('{0} commented on {1}', Purifier::clean($creator['profile']['first_name']), Purifier::clean($resource['name']))
    ])
]);

if ($showComment) {
    echo $this->element('Email/module/text', [
        'text' => Purifier::clean($comment['content'])
    ]);
}

echo $this->element('Email/module/button', [
    'url' => Router::url("/app/passwords/view/{$resource['id']}", true),
    'text' => __('View it in passbolt')
]);
