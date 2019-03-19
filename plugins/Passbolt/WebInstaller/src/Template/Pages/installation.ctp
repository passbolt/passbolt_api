<?php
use Cake\Routing\Router;
$this->Html->script('jquery-3.3.1.min.js', ['block' => 'scriptBottom']);
$this->Html->script('web_installer/installation', ['block' => 'scriptBottom']);
?>
<?php echo $this->element('header', ['title' => __('Some binary things are happening...')]) ?>
<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <?php echo $this->element('navigation', ['selectedSection' => 'installation']) ?>
    </div>
    <!-- main -->
    <?php echo $this->Form->create(); ?>
    <div class="panel middle">
        <?php echo $this->element('Passbolt/WebInstaller.install/installing'); ?>
        <?php echo $this->element('Passbolt/WebInstaller.install/complete'); ?>
        <?php echo $this->element('Passbolt/WebInstaller.install/errors'); ?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
