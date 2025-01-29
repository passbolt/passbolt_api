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
use Cake\I18n\FrozenTime;

$recipient = $body['recipient'];
$modifier = $body['modifier'];
$subject = Purifier::clean($body['subject']);
$settings = $body['settings'] ?? [];

echo $this->element('Email/module/avatar',[
    'url' => AvatarHelper::getAvatarUrl($modifier['profile']['avatar']),
    'text' => $this->element('Email/module/avatar_text', [
        'user' => $modifier,
        'datetime' => FrozenTime::now(),
        'text' => $subject
    ])
]);
if ($recipient['id'] === $modifier['id']) {
    $text = __('You edited the metadata settings.');
} else {
    $modifierFullName = Purifier::clean($modifier['profile']['first_name']) . ' ' . Purifier::clean($modifier['profile']['last_name']);
    $text = __('{0} edited the metadata settings.', $modifierFullName);
}
$text = '<h3>' . $text . '</h3><br/>';
foreach ($settings as $setting => $value) {
    $text .= $setting . ': ' . $value . '<br/>';
}
echo $this->element('Email/module/text', [
    'text' => $text
]);
