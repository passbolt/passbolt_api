<?php
use Cake\Routing\Router;
$this->Html->script('vendors/openpgp.min.js', ['block' => 'scriptBottom']);
$this->Html->script('web_installer/gpg_key_import', ['block' => 'scriptBottom']);
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
    <?php echo $this->Form->create($formExecuteResult); ?>
    <?php echo $this->Form->control('public_key_armored', ['type' => 'hidden']); ?>
    <?php echo $this->Form->control('private_key_armored', ['type' => 'hidden']); ?>
    <?php echo $this->Form->control('fingerprint', ['type' => 'hidden']); ?>

    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col6">
                    <h3><?= __('Copy paste the private key below'); ?></h3>
                    <?= $this->Flash->render() ?>
                    <?= $this->Form->control('armored_key', [
                        'type' => 'textarea',
                        'templates' => [
                            'inputContainer' => '<div class="input {{type}}{{required}} gpgkey">{{content}} <div class="message error hidden" aria-live="polite"></div></div>'
                        ]
                    ]); ?>
                    <div class="input file">
                        <input type="file" accept="text/plain,.key" id="key-file" class="hidden">
                        <a class="button" id="key-file-button" for="fileElem"><?= __('Browse'); ?></a>
                        <span class="help-text"><?= __('Or select a file from your computer'); ?></span>
                    </div>
                </div>
            </div>
            <div class="row last">
                <div class="input-wrapper">
                    <a href="<?= Router::url($stepInfo['previous'], true); ?>" class="button cancel big"><?= __('Cancel'); ?></a>
                    <button type="submit" id="next" class="button primary next big"><?= __('Next'); ?> </button>
                </div>
            </div>
        </div>
    </div>
    <?= $this->Form->end(); ?>
</div>
