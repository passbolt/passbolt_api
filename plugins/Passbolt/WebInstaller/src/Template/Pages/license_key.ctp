<?php
use Cake\Routing\Router;
$this->Html->script('jquery-3.3.1.min.js', ['block' => 'scriptBottom']);
$this->Html->script('web_installer/key_chooser', ['block' => 'scriptBottom']);
?>
<?php echo $this->element('header', ['title' => __('Passbolt Pro activation.')]) ?>
<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <?php echo $this->element('navigation', ['selectedSection' => 'license_key']) ?>
    </div>
    <!-- main -->
    <?php echo $this->Form->create($formExecuteResult); ?>
    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col7">
                        <div class="row">
                            <div class="col12">
                                <h3><?php echo __('Copy paste your Passbolt Pro subscription key here'); ?></h3>
                                <?php echo $this->Flash->render() ?>
                                <div class="input textarea gpgkey">
                                    <?php echo $this->Form->control('license_key', ['type' => 'textarea', 'class' => ['key-content']]); ?>
                                </div>
                                <div class="input file">
                                    <a role="button" class="button" id="key-chooser"><?php echo __('Browse'); ?></a>
                                    <span class="help-text"><?php echo __('Or select a file from your computer'); ?></span>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col5 last">
                    <?php echo $this->element('sidebar/license_key_explanations') ?>
                </div>
            </div>
            <div class="row last">
                <div class="input-wrapper">
                    <a href="<?php echo Router::url($stepInfo['previous'], true); ?>" class="button cancel big"><?php echo __('Cancel'); ?></a>
                    <input type="submit" class="button primary next big disabled" disabled="disabled" value="<?php echo __('Next'); ?>">
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
