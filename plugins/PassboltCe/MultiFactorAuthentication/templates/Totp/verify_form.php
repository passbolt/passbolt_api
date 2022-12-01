<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $providers
 * @var mixed $verifyForm
 */
    use Cake\Routing\Router;
    use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

    $title = __('Multi factor authentication verification');
    $this->assign('title', $title);
    $this->assign('pageClass', 'login v240918');
    $formContext = [
        'url' => Router::url('/mfa/verify/totp?redirect=' . $redirect, true),
        'class' => ['totp-setup'],
    ];
    ?>
<div class="login-form ">
    <h1>
        <?= __('Enter the six digit number as presented on your phone or tablet.'); ?>
    </h1>
    <?= $this->Form->create($verifyForm, $formContext); ?>
    <?= $this->Form->control('totp', [
        'label' => 'One Time Password (OTP)',
        'placeholder' => '123456',
        'autofocus' => 'autofocus'
    ]); ?>
    <div class="input checkbox">
        <input type="checkbox" name="remember" value="remember" id="remember">
        <label for="remember" ><?= __('Remember this device for a month.'); ?></label>
    </div>
    <?= $this->element('formActions', ['providers' => $providers, 'currentProvider' => MfaSettings::PROVIDER_TOTP]); ?>
    <?= $this->Form->end(); ?>
</div>
