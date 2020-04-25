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
 *
 * @see \Passbolt\Folders\Notification\Email\CreateFolderEmailRedactor
 */

use App\Model\Entity\User;
use App\Utility\Purifier;
use Cake\Routing\Router;
use Passbolt\Folders\Model\Entity\Folder;

if (PHP_SAPI === 'cli') {
    Router::fullBaseUrl($body['fullBaseUrl']);
}
/** @var User $user */
$user = $body['user'];
/** @var Folder $folder */
$folder = $body['folder'];

echo $this->element('Email/module/avatar',[
    'url' => Router::url(DS . $user->profile->avatar->url['small'], true),
    'text' => $this->element('Email/module/avatar_text', [
        'username' => Purifier::clean($user->username),
        'first_name' => Purifier::clean($user->profile->first_name),
        'last_name' => Purifier::clean($user->profile->last_name),
        'datetime' => $folder->created,
        'text' => __('You have created a new folder')
    ])
]);

$text = __('Name: {0}', Purifier::clean($folder->name)) . '<br/>';

echo $this->element('Email/module/text', [
    'text' => $text
]);
echo $this->element('Email/module/button', [
    'url' => Router::url("/app/folders/view/{$folder->id}", true),
    'text' => __('view it in passbolt')
]);
