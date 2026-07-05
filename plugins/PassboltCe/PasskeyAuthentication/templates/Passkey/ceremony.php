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
 * @var string $mode "setup" or "login"
 * @var string $userId
 * @var string|null $csrfToken
 *
 * Top-level ceremony page served from the passbolt origin so navigator.credentials.* binds to the
 * correct relying party id. It runs the WebAuthn call for the browser extension, which owns the
 * passphrase kit (the kit crypto and the begin/finish API calls happen in the extension, not here).
 * The bridge that hands the raw WebAuthn result to/from the extension is wired by the extension
 * pagemod; this page only exposes the ceremony parameters and a status region.
 */
$this->assign('title', $mode === 'login' ? __('Sign in with your security key') : __('Set up passkey login'));
$this->assign('pageClass', 'login');
$this->Html->script('Passbolt/PasskeyAuthentication.passkey-ceremony', ['block' => 'js', 'fullBase' => true]);
?>
<div class="login-form">
    <h1><?= $mode === 'login' ? __('Sign in with your security key.') : __('Set up passkey login.'); ?></h1>
    <div
        id="passkey-ceremony"
        data-mode="<?= h($mode); ?>"
        data-user-id="<?= h($userId); ?>"
        data-csrf-token="<?= h($csrfToken); ?>"
    >
        <p class="js-passkey-status" aria-live="polite">
            <?= __('Follow your browser prompts to use your security key.'); ?>
        </p>
        <p class="error-message js-passkey-error hidden" role="alert"></p>
    </div>
</div>
