<?php
use Cake\Routing\Router;
$this->Html->script('vendors/jquery.min.js', ['block' => 'scriptBottom']);
$this->Html->script('web_installer/key_chooser', ['block' => 'scriptBottom']);
?>
<?= $this->element('header', ['title' => __('Passbolt Pro activation.')]) ?>
<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <?= $this->element('navigation', ['selectedSection' => 'license_key']) ?>
    </div>
    <!-- main -->
    <?= $this->Form->create($formExecuteResult); ?>
    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col7">
                        <div class="row">
                            <div class="col12">
                                <h3><?= __('Copy paste your Passbolt Pro subscription key here'); ?></h3>
                                <?= $this->Flash->render() ?>
                                <div class="input textarea gpgkey">
                                    <?= $this->Form->control('license_key', ['type' => 'textarea', 'class' => ['key-content']]); ?>
                                </div>
                                <div class="input file">
                                    <a role="button" class="button" id="key-chooser"><?= __('Browse'); ?></a>
                                    <span class="help-text"><?= __('Or select a file from your computer'); ?></span>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col5 last">
                    <?= $this->element('sidebar/license_key_explanations') ?>
                </div>
            </div>
            <div class="row last">
                <div class="input-wrapper">
                    <a href="<?= Router::url($stepInfo['previous'], true); ?>" class="button cancel big"><?= __('Cancel'); ?></a>
                    <input type="submit" class="button primary next big disabled" disabled="disabled" value="<?= __('Next'); ?>">
                </div>
            </div>
        </div>
    </div>
    <?= $this->Form->end(); ?>
</div>
