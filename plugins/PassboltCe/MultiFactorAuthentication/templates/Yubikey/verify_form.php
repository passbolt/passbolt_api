<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $providers
 * @var mixed $verifyForm
 * @var bool $isRememberMeForAMonthEnabled
 */
    use Cake\Routing\Router;
    use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

    $title = __('Multi factor authentication verification');
    $this->assign('title', $title);
    $this->assign('pageClass', 'login');
    $formContext = [
        'url' => Router::url('/mfa/verify/yubikey?redirect=' . $redirect, true),
        'class' => ['yubikey-setup'],
    ];
    ?>
<div class="login-form ">
    <h1>
        <?= __('Plug in the yubikey and put your finger on it.'); ?>
    </h1>
    <?= $this->Form->create($verifyForm, $formContext); ?>
    <?= $this->Form->control('hotp', [
        'label' => 'Yubikey OTP',
        'type' => 'password',
        'autofocus' => 'autofocus',
        'autocomplete' => 'off'
    ]); ?>

    <?php if ($isRememberMeForAMonthEnabled): ?>
    <div class="input checkbox">
        <input type="checkbox" name="remember" value="remember" id="remember">
        <label for="remember" ><?= __('Remember this device for a month.'); ?></label>
    </div>
    <?php endif; ?>

    <?= $this->element('formActions', ['providers' => $providers, 'redirect' => $redirect, 'currentProvider' => MfaSettings::PROVIDER_YUBIKEY]); ?>
    <?= $this->Form->end(); ?>
</div>
