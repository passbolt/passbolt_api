<?php
use Cake\Routing\Router;
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
                    <h3><?= __('The installation is complete') ?></h3>
                    <div class="message success">
                        <strong><i class="fa fa-check-circle"></i> Success!</strong>
                        You have completed successfully the installation procedure, congrats!
                        You will soon be redirected to passbolt to complete your account setup.
                    </div>
                    <?= Router::url('/setup/install/' . $token['user_id'] . '/' . $token['token'], true) ?>
                </div>
                <div class="col5 last">
                </div>
            </div>
            <div class="row last">
                <div class="input-wrapper">
                    <a href="#" class="button primary next big processing">next</a>
                    <a href="<?= Router::url('/setup/install/' . $token['user_id'] . '/' . $token['token'], true) ?>">Click here if you can't wait</a>
                </div>
            </div>
        </div>
    </div>
    <?= $this->Form->end(); ?>
</div>
