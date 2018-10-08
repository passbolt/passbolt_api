<?php
    use Cake\Core\Configure;
    $title = __('Multi factor authentication verification');
    $this->assign('title', $title);
    $this->Html->css('themes/default/api_login.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);
    $this->assign('pageClass', 'mfa');
?>
<div class="mfa wizard verify">
    <div class="grid grid-responsive-12">
        <?= $this->form->create($totpVerifyForm); ?>
            <div class="row">
                <div class="col6 last">
                    <h3><?= $title; ?></h3
                    <p>
                        <?= __('Enter the six digit number as presented on your phone or tablet.'); ?>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col6">
                    <div class="input-verify">
                        <?= $this->form->control('otp', [
                                'label' => 'One Time Password (OTP)',
                                'placeholder' => '123456'
                        ]); ?>
                    </div>
                    <p class="input checkbox">
                        <input type="checkbox" name="remember" value="remember" id="remember">
                        <label for="remember" ><?= __('Remember this device for a month.'); ?></label>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="actions-wrapper">
                    <?= $this->Form->button('Verify', ['type' => 'submit', 'class' => 'button primary big']); ?>
                </div>
            </div>
        <?= $this->form->end(); ?>
    </div>
</div>