<?php
    use Cake\Core\Configure;
    use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

    $title = __('Multi factor authentication verification');
    $this->assign('title', $title);
    $this->Html->css('themes/anew/api_login.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);
    $this->assign('pageClass', 'login v240918');
?>
<div class="login-form ">
    <h1>
        <?= __('Enter the six digit number as presented on your phone or tablet.'); ?>
    </h1>
    <?= $this->form->create($verifyForm); ?>
    <?= $this->form->control('totp', [
        'label' => 'One Time Password (OTP)',
        'placeholder' => '123456'
    ]); ?>
    <div class="input checkbox">
        <input type="checkbox" name="remember" value="remember" id="remember">
        <label for="remember" ><?= __('Remember this device for a month.'); ?></label>
    </div>
    <?= $this->element('formActions', ['providers' => $providers, 'currentProvider' => MfaSettings::PROVIDER_TOTP]); ?>
    <?= $this->form->end(); ?>
</div>