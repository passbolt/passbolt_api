<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $yubikeySetupForm
 */
    use Cake\Core\Configure;
    use Cake\Routing\Router;

    $title = __('Yubikey One Time Password');
    $this->assign('title', $title);
    $this->assign('pageClass', 'iframe mfa');
    $formContext = [
        'url' => Router::url('/mfa/setup/yubikey', true),
        'class' => ['yubikey-setup'],
    ];
    ?>
<div class="grid grid-responsive-12">
    <?= $this->Form->create($yubikeySetupForm, $formContext); ?>
    <div class="row">
        <div class="col7 main-column">
            <h3><?= $title; ?></h3>
            <div class="input-verify">
                <?= $this->Form->control('hotp', [
                    'label' => 'Yubikey OTP',
                    'type' => 'password',
                    'autocomplete' => 'off'
                ]); ?>
                <div class="helptext">
                    <?= __('Plug in the yubikey and put your finger on it.'); ?>
                </div>
            </div>
            <div class="actions-wrapper">
                <a href="<?= Router::url('/mfa/setup/select', true); ?>" class="button cancel">Cancel</a>
                <button type="submit" class="button primary">Validate</button>
            </div>
        </div>
    </div>
    <?= $this->Form->end(); ?>
</div>
