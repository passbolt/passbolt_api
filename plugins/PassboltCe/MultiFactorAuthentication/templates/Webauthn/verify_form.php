<?php
/**
 * @var \App\View\AppView $this
 * @var array $providers
 * @var bool $isRememberMeForAMonthEnabled
 * @var string $redirect
 */

use App\Utility\Purifier;
use Cake\Routing\Router;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

$title = __('Multi factor authentication verification');
$this->assign('title', $title);
$this->assign('pageClass', 'login');
$safeRedirect = Purifier::clean($redirect);
$formUrl = Router::url('/mfa/verify/webauthn?redirect=' . $redirect, true);
?>
<div class="content">
    <div class="header">
        <div class="logo"><span class="visually-hidden">Passbolt</span></div>
    </div>
    <div class="login-form">
        <?= $this->Html->image('/img/mfa/passkey-mark.png', [
            'alt' => __('Passkey'),
            'class' => 'passkey-mark',
            'width' => 120,
            'height' => 120,
        ]) ?>
        <h1><?= __('Additional Authentication with Passkey'); ?></h1>

        <form action="<?= Purifier::clean($formUrl) ?>"
              method="post"
              id="webauthn-verify-form"
              class="webauthn-verify"
              data-redirect="<?= h($safeRedirect) ?>">

            <?php if ($isRememberMeForAMonthEnabled) : ?>
            <div class="input checkbox">
                <input type="checkbox" name="remember" value="remember" id="webauthn-remember">
                <label for="webauthn-remember"><?= __('Remember this device for a month.'); ?></label>
            </div>
            <?php endif; ?>

            <?= $this->element('formActions', [
                'providers' => $providers,
                'redirect' => $redirect,
                'currentProvider' => MfaSettings::PROVIDER_WEBAUTHN,
            ]); ?>
        </form>
    </div>
</div>
<?= $this->Html->script('/js/mfa/webauthn-verify-fallback.js') ?>
