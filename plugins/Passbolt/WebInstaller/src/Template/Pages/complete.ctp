<?php
use Cake\Routing\Router;
$this->Html->script('jquery-3.3.1.min.js', ['block' => 'scriptBottom']);
$this->Html->script('Passbolt/WebInstaller.redirect', ['block' => 'scriptBottom']);
?>
<?= $this->element('header', ['title' => __('You\'ve made it!')]) ?>
<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <?= $this->element('navigation', ['selectedSection' => 'end']) ?>
    </div>
    <!-- main -->
    <?= $this->Form->create(); ?>
    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col7">
                    <h3><?= __('The configuration is complete') ?></h3>
                    <div class="message success">
                        <strong><i class="fa fa-check-circle"></i> <?= __('Success!') ?></strong>
                        <?= __('You have completed successfully the configuration procedure, congrats!') ?>
                        <?php if (isset($token)) : ?>
                        <?= __('You will soon be redirected to passbolt to complete your account setup.') ?>
                        <?php else: ?>
                        <?= __('You will soon be redirected to the login page.') ?>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="redirect-url" id="redirect-url" value="<?= Router::url($redirectUrl, true) ?>">
                </div>
                <div class="col5 last">
                </div>
            </div>
            <div class="row last">
                <div class="input-wrapper">
                    <a href="#" class="button primary next big processing"><?= __('next') ?></a>
                    <a href="<?= Router::url($redirectUrl, true) ?>"><?= __('Click here if you can\'t wait') ?></a>
                </div>
            </div>
        </div>
    </div>
    <?= $this->Form->end(); ?>
</div>
