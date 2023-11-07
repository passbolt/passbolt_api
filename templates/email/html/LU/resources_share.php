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
 */

use App\Model\Entity\User;
use App\Utility\Purifier;
use App\View\Helper\AvatarHelper;
use Cake\I18n\FrozenTime;
use Cake\Routing\Router;

if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
/** @var User $owner */
$owner = $body['owner'];
$count = $body['count'];

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($owner['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $owner,
        'datetime' => FrozenTime::now(),
        'text' => __('{0} shared passwords with you', Purifier::clean($owner['profile']['first_name']))
    ])
]);

$text = __('{0} resources were shared with you.', $count) . ' ';
$text .= __('It would be too much to list them here, but you can go check them on passbolt.');

echo $this->element('Email/module/text', [
    'text' => $text
]);

echo $this->element('Email/module/button', [
    'url' => Router::url('/app/passwords', true),
    'text' => __('View them in passbolt')
]);
