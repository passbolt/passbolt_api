<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $setupForm
 */
    use Cake\Routing\Router;

    $title = __('Getting started with Duo');
    $this->assign('title', $title);
    $this->assign('pageClass', 'iframe mfa');
    $redirect = '/app/settings/mfa';
    $formContext = [
        'url' => Router::url('/mfa/setup/duo/prompt?redirect=' . $redirect, true),
        'target' => '_top',
        'id' => 'duo_form',
    ];
    ?>
<div class="grid grid-responsive-12">
    <div class="row">
        <div class="col7 main-column how-it-works">
            <h3><?= $title; ?></h3>
            <h4 class="no-border"><?= __('How does it work?'); ?></h4>
            <img src="img/diagrams/duo.svg" style="margin-left:12px" alt="<?= __('diagram showing how it works'); ?>"/>
            <div class="instruction">
                <p><?= __('You sign in to passbolt just like you normally do.'); ?></p>
                <p style="text-align:right; padding-left:12px"><?= __('Use you 2FA device to authenticate.'); ?></p>
                <p style="text-align:right"><?= __('Follow the procedure to login.'); ?></p>
            </div>
            <?= $this->Form->create($setupForm, $formContext); ?>
                <div class="actions-wrapper" style="margin-top:32px">
                    <a href="<?= Router::url('/mfa/setup/select', true); ?>" class="button cancel"><?= __('Cancel'); ?></a>
                    <button type="submit" class="button primary"><?= __('Sign-in with Duo'); ?></button>
                </div>
            <?= $this->Form->end(); ?>
        </div>
        <div class="col4 last">
            <div class="sidebar-help">
                <h3><?= __('Requirements') ?></h3>
                <p>
                    <?= __('To proceed, you need to install the Duo mobile application or to have a device to authenticate which is supported by Duo.'); ?> <?= __('For the list of supported devices, see:'); ?>
                    <a href="https://duo.com/product/multi-factor-authentication-mfa/authentication-methods" target="_blank" rel="noopener noreferrer"><?= __('Duo authentication methods'); ?></a>.
                </p>
                <a href="https://help.passbolt.com/configure/mfa/duo.html" target="_blank" rel="noopener noreferrer" class="button"><?= __('Learn more'); ?></a>
            </div>
        </div>
    </div>
</div>
