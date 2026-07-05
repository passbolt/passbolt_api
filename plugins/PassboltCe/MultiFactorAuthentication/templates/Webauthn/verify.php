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
 * @var array $providers
 * @var bool $isRememberMeForAMonthEnabled
 * @var string $redirect
 * @var string|null $csrfToken
 */

use App\Utility\Purifier;
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

$this->assign('title', __('Multi factor authentication verification'));
$this->assign('pageClass', 'login');
$this->Html->script('Passbolt/MultiFactorAuthentication.webauthn', ['block' => 'js', 'fullBase' => true]);

// Compute the "try another provider" target (next enabled provider).
$otherProvider = null;
if (isset($providers) && count($providers) > 1) {
    $index = array_search(MfaSettings::PROVIDER_WEBAUTHN, $providers);
    if ($index !== false) {
        $index = ($index === count($providers) - 1) ? 0 : $index + 1;
        $otherProvider = $providers[$index];
    }
}
?>
<div class="login-form">
    <h1><?= __('Use your security key.'); ?></h1>
    <div
        id="webauthn-ceremony"
        data-mode="verify"
        data-begin-url="<?= Purifier::clean(Router::url('/mfa/verify/webauthn/begin.json', true)); ?>"
        data-finish-url="<?= Purifier::clean(Router::url('/mfa/verify/webauthn/finish.json', true)); ?>"
        data-redirect="<?= Purifier::clean(Router::url($redirect, true)); ?>"
        data-csrf-token="<?= h($csrfToken); ?>"
    >
        <p class="js-webauthn-status" aria-live="polite">
            <?= __('Click the button below, then follow your browser prompts.'); ?>
        </p>
        <p class="error-message js-webauthn-error hidden" role="alert"></p>

        <?php if ($isRememberMeForAMonthEnabled) : ?>
        <div class="input checkbox">
            <input type="checkbox" name="remember" value="remember" id="remember" class="js-webauthn-remember">
            <label for="remember"><?= __('Remember this device for a month.'); ?></label>
        </div>
        <?php endif; ?>

        <div class="form-actions">
            <button
                type="button"
                class="button primary big full-width js-webauthn-trigger"
                data-busy-label="<?= h(__('Waiting for your security key…')); ?>"
            >
                <?= __('Use security key'); ?>
            </button>
            <?php if ($otherProvider !== null) : ?>
            <a href="<?= Purifier::clean(Router::url('/mfa/verify/' . $otherProvider . '?redirect=' . $redirect, true)); ?>">
                <?= __('Or try with another provider'); ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
