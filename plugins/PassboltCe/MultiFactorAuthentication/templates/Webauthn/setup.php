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
 * @var string $redirect
 * @var string|null $csrfToken
 */

use App\Utility\Purifier;
use Cake\Routing\Router;

$this->assign('title', __('Set up a security key'));
$this->assign('pageClass', 'login');
$this->Html->script('Passbolt/MultiFactorAuthentication.webauthn', ['block' => 'js', 'fullBase' => true]);
?>
<div class="login-form">
    <h1><?= __('Register your security key.'); ?></h1>
    <div
        id="webauthn-ceremony"
        data-mode="setup"
        data-begin-url="<?= Purifier::clean(Router::url('/mfa/setup/webauthn/begin.json', true)); ?>"
        data-finish-url="<?= Purifier::clean(Router::url('/mfa/setup/webauthn/finish.json', true)); ?>"
        data-redirect="<?= Purifier::clean(Router::url($redirect, true)); ?>"
        data-csrf-token="<?= h($csrfToken); ?>"
    >
        <p class="js-webauthn-status" aria-live="polite">
            <?= __('Name your security key, click register, then follow your browser prompts.'); ?>
        </p>
        <p class="error-message js-webauthn-error hidden" role="alert"></p>

        <div class="input text">
            <label for="webauthn-name"><?= __('Security key name'); ?></label>
            <input
                type="text"
                id="webauthn-name"
                class="js-webauthn-name"
                maxlength="255"
                autocomplete="off"
                placeholder="<?= __('e.g. My security key'); ?>"
            >
        </div>

        <div class="form-actions">
            <button
                type="button"
                class="button primary big full-width js-webauthn-trigger"
                data-busy-label="<?= h(__('Waiting for your security key…')); ?>"
            >
                <?= __('Register security key'); ?>
            </button>
            <a href="<?= Purifier::clean(Router::url($redirect, true)); ?>"><?= __('Cancel'); ?></a>
        </div>
    </div>
</div>
