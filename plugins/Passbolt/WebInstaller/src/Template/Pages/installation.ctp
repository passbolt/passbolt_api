<?php
use Cake\Routing\Router;
$this->Html->script('jquery-3.3.1.min.js', ['block' => 'scriptBottom']);
$this->Html->script('Passbolt/WebInstaller.installation', ['block' => 'scriptBottom']);
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
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col7">
                    <h3><?= __('Installing') ?></h3>
                    <div class="progress-bar-wrapper">
                        <span class="progress-bar big infinite"><span class="progress "></span></span>
                    </div>

                    <p class="install-details">Installing database</p>
                    <input type="hidden" name="install" id="install-url" value="<?= Router::url($stepInfo['install'], true) ?>">
                    <input type="hidden" name="redirect" id="redirect-url" value="<?= Router::url($redirectUrl, true) ?>">
                </div>
                <div class="col5 last">
                </div>
            </div>
            <div class="row last">
                <div class="input-wrapper">
                    <a href="#" class="button primary next big processing">next</a>
                </div>
            </div>
        </div>
    </div>
    <?= $this->Form->end(); ?>
</div>
