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
 * @since         5.14.0
 * @var \App\View\AppView $this
 *
 * Landing page the ceremony navigates to once the credential has been deposited. The browser
 * extension watches the popup url (which carries the challenge token) and closes this window.
 */
$this->assign('title', __('Almost done'));
$this->assign('pageClass', 'login');
?>
<div class="login-form">
    <h1><?= __('You can close this window.'); ?></h1>
    <p><?= __('Your security key has been used. Please return to Passbolt to continue.'); ?></p>
</div>
