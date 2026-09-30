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
 * @var \App\View\AppView $this
 * @var string $redirectUri
 * @var string $errorCode
 */

$this->assign('title', __('Multi factor authentication verification'));
$this->assign('pageClass', 'login v240918');
?>
<div class="content">
    <div class="header">
        <div class="logo"><span class="visually-hidden">Passbolt</span></div>
    </div>
    <div class="login-form" id="mfa-webauthn-delegated-expired">
        <h1><?= __('This verification link is no longer valid.') ?></h1>
        <p><?= __('It has already been used, or it expired. Start the verification again from the application.') ?></p>
        <div class="form-actions">
            <a class="button primary big full-width"
               href="<?= h($redirectUri) ?>?error=<?= h($errorCode) ?>"><?= __('Return to the application') ?></a>
        </div>
    </div>
</div>
