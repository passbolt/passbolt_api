<?php
    use Cake\Core\Configure;
    use Cake\Routing\Router;
    $title = __('Time based One Time Password (TOTP)');
    $this->assign('title', $title);
    $version = Configure::read('passbolt.version');
    $themePath = "themes/$theme/api_main.min.css?v=$version";
    $this->Html->css($themePath, ['block' => 'css', 'fullBase' => true]);
    $this->assign('pageClass', 'iframe mfa');
    $formContext = [
        'url' => Router::url('/mfa/setup/totp', true),
        'class' => ['totp-setup']
    ];
?>
<div class="grid grid-responsive-12">
    <?= $this->form->create($totpSetupForm, $formContext); ?>
        <div class="row">
            <div class="col12 last">
                <h3><?= $title; ?></h3>
            </div>
        </div>
        <div class="row">
            <div class="col7">
                <h4><?= __('Scan this bar code'); ?></h4>
                <?= $this->form->hidden('otpQrCodeImage'); ?>

                <?= $this->form->hidden('otpProvisioningUri'); ?>

                <img class="qrcode" src="<?= $this->request->getData('otpQrCodeImage'); ?>" width="128" height="128"/>
                <div class="input-verify">
                    <?= $this->form->control('totp', [
                        'label' => 'One Time Password (OTP)',
                        'placeholder' => '123456'
                    ]); ?>
                    <div class="helptext">
                        <?= __('Enter the six digit number as presented on your phone or tablet.'); ?>
                    </div>
                </div>
            </div>
            <div class="col4 last">
                <h4><?= __('Requirements'); ?></h4>
                <div class="message notice">
                    <p>
                       <?= __('To proceed you need to install an application that supports Time Based One Time Passwords (TOTP) on your phone or tablet such as:'); ?>
                        <a href="https://support.google.com/accounts/answer/1066447" target="_blank" rel="noopener">Google Authenticator</a> <?= __('or'); ?>
                        <a href="https://freeotp.github.io/" target="_blank" rel="noopener">FreeOTP</a>.
                    </p>
                    <a href="https://help.passbolt.com/start/" target="_blank" rel="noopener" class="button"><?= __('learn more'); ?></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col7 last">
                <div class="actions-wrapper">
                    <div class="actions-wrapper">
                        <a href="<?= Router::url('/mfa/setup/totp/start', true); ?>" class="button cancel">Cancel</a>
                        <input type="submit" class="button primary" value="Validate">
                    </div>
                </div>
            </div>
        </div>
    <?= $this->form->end(); ?>

</div>