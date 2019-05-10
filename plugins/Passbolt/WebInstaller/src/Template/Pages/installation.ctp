<?php
use Cake\Routing\Router;
$this->Html->script('vendors/jquery.min.js', ['block' => 'scriptBottom']);
$this->Html->script('web_installer/installation', ['block' => 'scriptBottom']);
?>
<?= $this->element('header', ['title' => __('Some binary things are happening...')]) ?>
<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <?= $this->element('navigation', ['selectedSection' => 'installation']) ?>
    </div>
    <!-- main -->
    <?= $this->Form->create(); ?>
    <div class="panel middle">
        <?php echo $this->element('Passbolt/WebInstaller.install/installing'); ?>
        <?php echo $this->element('Passbolt/WebInstaller.install/complete'); ?>
        <?php echo $this->element('Passbolt/WebInstaller.install/errors'); ?>
    </div>
    <?= $this->Form->end(); ?>
</div>
