<?php
    use Cake\Core\Configure;
    $title = __('Multi factor authentication setup');
    $this->assign('title', $title);
    $this->Html->css('themes/default/api_login.min.css?v=' . Configure::read('passbolt.version'), ['block' => 'css', 'fullBase' => true]);
    $this->assign('pageClass', 'mfa');
?>
<div class="mfa wizard verify">
    <div class="grid grid-responsive-12">
        <?= $this->form->create($totpSetupForm); ?>
            <div class="row">
                <div class="col6 last">
                    <h3><?= $title; ?></h3
                    <p>
                        <?= __('Scan this bar code using your phone or your tablet using an application that supports one time passwords such as Google Authenticator or FreeOTP.'); ?>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col3">
                    <?= $this->form->hidden('otpQrCodeImage'); ?>
                    <?= $this->form->hidden('otpProvisioningUri'); ?>
                    <img src="<?= $this->request->getData('otpQrCodeImage'); ?>" width="256" height="256"/>
                </div>
                <div class="col4">
                    <div class="input-verify">
                        <?= $this->form->control('otp', [
                                'label' => 'One Time Password (OTP)',
                                'placeholder' => '123456'
                        ]); ?>
                        <div class="message helptext">
                            <?= __('Enter the six digit number as presented on your phone or tablet.'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="actions-wrapper">
                    <?= $this->Form->button('Cancel', ['type' => 'button', 'class' => 'button cancel big']); ?>
                    <?= $this->Form->button('Validate', ['type' => 'submit', 'class' => 'button primary big']); ?>
                </div>
            </div>
        <?= $this->form->end(); ?>
    </div>
</div>