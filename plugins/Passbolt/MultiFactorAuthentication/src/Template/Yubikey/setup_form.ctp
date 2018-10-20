<?php
use Cake\Core\Configure;
use Cake\Routing\Router;
$title = __('Yubikey One Time Password');
$this->assign('title', $title);
$version = Configure::read('passbolt.version');
$themePath = "themes/$theme/api_main.min.css?v=$version";
$this->Html->css($themePath, ['block' => 'css', 'fullBase' => true]);
$this->assign('pageClass', 'iframe mfa');
?>
<div class="grid grid-responsive-12">
    <?= $this->form->create($yubikeySetupForm, ['class' => ['yubikey-setup']]); ?>
    <div class="row">
        <div class="col12 last">
            <h3><?= $title; ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col7">
            <h4><?= __('Scan this bar code'); ?></h4>
            <div class="input-verify">
                <?= $this->form->control('hotp', [
                    'label' => 'Yubikey OTP',
                    'type' => 'password'
                ]); ?>
                <div class="helptext">
                    <?= __('Put your finger on your yubikey.'); ?>
                </div>
            </div>
        </div>
        <div class="col4 last">
            <h4><?= __('Requirements'); ?></h4>
            <div class="message notice">
                <p>
                    TODO
                </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col7 last">
            <div class="actions-wrapper">
                <a href="<?= Router::url('/mfa/setup/select'); ?>" class="button cancel">Cancel</a>
                <input type="submit" class="button primary" value="Validate">
            </div>
        </div>
    </div>
    <?= $this->form->end(); ?>

</div>