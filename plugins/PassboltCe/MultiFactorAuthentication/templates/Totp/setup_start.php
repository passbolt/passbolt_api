<?php
/**
 * @var \App\View\AppView $this
 */
    use Cake\Core\Configure;
    use Cake\Routing\Router;

    $title = __('Getting started with Time based One Time Password (TOTP)');
    $this->assign('title', $title);
    $this->assign('pageClass', 'iframe mfa');
?>
<div class="grid grid-responsive-12">
    <div class="row">
        <div class="col7 main-column how-it-works">
            <h3><?= $title; ?></h3>
            <h4 class="no-border"><?= __('How does it work?'); ?></h4>
            <img src="img/diagrams/totp.svg" alt="<?= __('diagram showing how it works'); ?>"/>
            <div class="instruction">
                <p><?= __('You sign in to passbolt just like you normally do.'); ?></p>
                <p><?= __('When using a new browser, you need an additional code from your phone.'); ?></p>
                <p><?= __('Once you enter this code, you can log in.'); ?></p>
            </div>
            <div class="actions-wrapper">
                <a class="button cancel" href="<?= Router::url('/mfa/setup/select', true); ?>">
                    <?= __('cancel'); ?>
                </a>
                <a class="button primary" href="<?= Router::url('/mfa/setup/totp', true); ?>">
                    <?= __('get started!'); ?>
                </a>
            </div>
        </div>
        <div class="col4 last">
            <div class="sidebar-help">
                <h3><?= __('Requirements') ?></h3>
                <p>
                    <?= __('To proceed you need to install an application that supports Time Based One Time Passwords (TOTP) on your phone or tablet such as:'); ?>
                    <a href="https://support.google.com/accounts/answer/1066447" target="_blank" rel="noopener">Google Authenticator</a> <?= __('or'); ?>
                    <a href="https://freeotp.github.io/" target="_blank" rel="noopener">FreeOTP</a>.
                </p>
                <a href="https://help.passbolt.com/start/" target="_blank" rel="noopener" class="button"><?= __('learn more'); ?></a>
            </div>
        </div>
    </div>
</div>
