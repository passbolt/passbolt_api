<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $totpSetupForm
 */
    use Cake\Core\Configure;
    use Cake\Routing\Router;

    $title = __('Time based One Time Password (TOTP)');
    $this->assign('title', $title);
    $this->assign('pageClass', 'iframe mfa');
    $formContext = [
        'url' => Router::url('/mfa/setup/totp', true),
        'class' => ['totp-setup'],
    ];
    ?>
<div class="grid grid-responsive-12">
    <?= $this->Form->create($totpSetupForm, $formContext); ?>
        <div class="row">
            <div class="col7 main-column">
                <h3><?= $title; ?></h3>
                <h4 class="no-border"><?= __('Scan this bar code'); ?></h4>
                <?= $this->Form->hidden('otpQrCodeSvg'); ?>

                <?= $this->Form->hidden('otpProvisioningUri'); ?>
                <div class="qrcode">
                    <?= $this->request->getData('otpQrCodeSvg'); ?>
                </div>
                <div class="input-verify">
                    <?= $this->Form->control('totp', [
                        'label' => 'One Time Password (OTP)',
                        'placeholder' => '123456',
                        'autocomplete' => 'off'
                    ]); ?>
                    <div class="helptext">
                        <?= __('Enter the six digit number as presented on your phone or tablet.'); ?>
                    </div>
                </div>
                <div class="actions-wrapper">
                    <a href="<?= Router::url('/mfa/setup/totp/start', true); ?>" class="button cancel">Cancel</a>
                    <button type="submit" class="button primary">Validate</button>
                </div>
            </div>
            <div class="col4 last">
                <div class="sidebar-help">
                    <h3><?= __('Requirements'); ?></h3>
                    <p>
                       <?= __('To proceed you need to install an application that supports Time Based One Time Passwords (TOTP) on your phone or tablet such as:'); ?>
                        <a href="https://support.google.com/accounts/answer/1066447" target="_blank" rel="noopener">Google Authenticator</a> <?= __('or'); ?>
                        <a href="https://freeotp.github.io/" target="_blank" rel="noopener">FreeOTP</a>.
                    </p>
                    <a href="https://help.passbolt.com/start/" target="_blank" rel="noopener" class="button"><?= __('learn more'); ?></a>
                </div>
            </div>
        </div>
    <?= $this->Form->end(); ?>

</div>
