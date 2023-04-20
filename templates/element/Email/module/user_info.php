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
 * @since         3.6.0
 *
 * @var string $userAgent
 * @var string $clientIp
 */

use App\Utility\Purifier;
use Cake\Core\Configure;

$canSeeUserIp = Configure::read('passbolt.security.userIp');
$canSeeUserAgent = Configure::read('passbolt.security.userAgent');

$text = '';

if ($canSeeUserAgent) {
    $text .= "User Agent: <i>" . Purifier::clean($userAgent) . "</i>";
}
if ($canSeeUserIp && $canSeeUserAgent) {
    $text .= "<br/>";
}
if ($canSeeUserIp) {
    $text .= "User IP: <i>" . Purifier::clean($clientIp) . "</i>";
}

echo $this->element('Email/module/text', [
    'text' => $text
]);
