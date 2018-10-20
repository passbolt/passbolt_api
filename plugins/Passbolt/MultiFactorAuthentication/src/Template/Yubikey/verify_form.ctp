<?php
    use Cake\Core\Configure;
    $title = __('Multi factor authentication verification');
    $this->assign('title', $title);
    $this->Html->css('themes/anew/api_login.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);
    $this->assign('pageClass', 'login v240918');
?>
<div class="login-form ">
    <h1>
        <?= __('Put your finger on the yubikey'); ?>
    </h1>
    <?= $this->form->create($verifyForm); ?>
    <?= $this->form->control('hotp', [
        'label' => 'Yubikey OTP',
        'type' => 'password'
    ]); ?>
    <div class="input checkbox">
        <input type="checkbox" name="remember" value="remember" id="remember" checked>
        <label for="remember" ><?= __('Remember this device for a month.'); ?></label>
    </div>
    <div class="form-actions">
        <button type="submit" class="button primary big" role="button"><?= __('verify'); ?></button>
    </div>
    <?= $this->form->end(); ?>
</div>