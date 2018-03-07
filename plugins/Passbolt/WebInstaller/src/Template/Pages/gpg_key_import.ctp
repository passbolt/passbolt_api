<?php
use Cake\Routing\Router;
$this->Html->script('jquery-3.3.1.min.js', ['block' => 'scriptBottom']);
$this->Html->script('Passbolt/WebInstaller.key_chooser', ['block' => 'scriptBottom']);
?>
<?= $this->element(
    'header', [
        'title' => __('Import an existing key or {0} a new one.', [
            '<a href="' . (Router::url($stepInfo['generate_key_cta'], true)) . '" class="button primary">' . __('create') . '</a>'
        ])
    ]) ?>
<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <?= $this->element('navigation', ['selectedSection' => 'server_keys']) ?>
    </div>
    <!-- main -->
    <?= $this->Form->create($gpgKeyImportForm); ?>
    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col6">
                    <h3><?= __('Copy paste the private key below'); ?></h3>
                    <?= $this->Flash->render() ?>
                    <div class="input textarea gpgkey">
                        <?= $this->Form->textarea('armored_key', ['class' => ['key-content']]); ?>
                    </div>
                    <div class="input file">
                        <a role="button" class="button" id="key-chooser"><?= __('Browse'); ?></a>
                        <span class="help-text"><?= __('Or select a file from your computer'); ?></span>
                    </div>
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
