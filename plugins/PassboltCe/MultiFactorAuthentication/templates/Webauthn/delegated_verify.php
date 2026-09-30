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
 * @var string $state
 * @var string $beginUrl
 * @var string $finishUrl
 * @var string $redirectUri
 */

use Cake\Core\Configure;

$version = Configure::read('passbolt.version');

$this->assign('title', __('Multi factor authentication verification'));
$this->assign('pageClass', 'login v240918');

$this->Html->script('/js/mfa/webauthn-verify-delegated.js?v=' . $version, [
    'block' => 'js',
    'fullBase' => true,
    'defer' => true,
    'cache-version' => $version,
]);
?>
<div class="content">
    <div class="header">
        <div class="logo"><span class="visually-hidden">Passbolt</span></div>
    </div>
    <div class="login-form">
        <h1><?= __('Verify with your passkey.') ?></h1>
        <p><?= __('Follow the instructions of your browser to use your passkey.') ?></p>
        <div id="mfa-webauthn-delegated"
             data-state="<?= h($state) ?>"
             data-begin-url="<?= h($beginUrl) ?>"
             data-finish-url="<?= h($finishUrl) ?>"
             data-redirect-uri="<?= h($redirectUri) ?>">
            <div id="mfa-webauthn-delegated-error" class="message error" hidden></div>
            <div class="form-actions">
                <button type="button" id="mfa-webauthn-delegated-verify" class="button primary big full-width">
                    <?= __('verify') ?>
                </button>
                <!-- Shown when browser does not automatically redirect to application. -->
                <button type="button" id="mfa-webauthn-delegated-return" class="button primary big full-width" hidden>
                    <?= __('Return to the application') ?>
                </button>
            </div>
        </div>
    </div>
</div>
