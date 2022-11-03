<?php
use Cake\Routing\Router;

$this->Html->script('vendors/openpgp.min.js', ['block' => 'scriptBottom']);
$this->Html->script('web_installer/gpg_key_generate', ['block' => 'scriptBottom']);
?>
<?= $this->element('header', [
    'title' => __('Create a new server OpenPGP key or {0} an existing one.', [
        '<a href="' . Router::url($stepInfo['import_key_cta'], true) . '" class="button primary">' . __('import') . '</a>',
    ]),
]) ?>
<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <?= $this->element('navigation', ['selectedSection' => 'server_keys']) ?>
    </div>
    <!-- main -->
    <?= $this->Form->create($formExecuteResult); ?>
    <?php $this->Form->setTemplates(['inputContainer' => '<div class="input {{type}}{{required}}">{{content}} <div class="message error-message hidden" aria-live="polite"></div></div>']); ?>
    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col6">
                    <h2><?= __('Create a new OpenPGP key for your server'); ?></h2>
                    <?= $this->Flash->render() ?>
                    <?php
                    echo $this->Form->control('public_key_armored', ['type' => 'hidden']);
                    echo $this->Form->control('private_key_armored', ['type' => 'hidden']);
                    echo $this->Form->control('fingerprint', ['type' => 'hidden']);
                    echo $this->Form->control('name', [
                        'required' => 'required',
                        'placeholder' => __('My company server name'),
                        'label' => __('Server Name'),
                        'class' => 'required fluid',
                    ]);

                    echo $this->Form->control('email', [
                        'required' => 'required',
                        'type' => 'text',
                        'placeholder' => __('admin@your-server.com'),
                        'label' => __('Server Email'),
                        'class' => 'required fluid',
                    ]);

                    echo $this->Form->control('comment', [
                        'placeholder' => __('add a comment (optional)'),
                        'label' => __('Comment'),
                        'class' => 'fluid',
                    ]);
                    ?>
                </div>
                <div class="col4 last">
                    <h2>Advanced settings</h2>
                    <div class="input select required">
                        <label for="KeyType">Key Type</label>
                        <select name="data[Key][type]" id="KeyType" disabled="disabled" class="fluid">
                            <option value="RSA-DSA" selected="selected">RSA and DSA (default)</option>
                            <option value="DSA-EL" >DSA and Elgamal</option>
                        </select>
                    </div>
                    <div class="input select required">
                        <label for="KeyLength">Key Length</label>
                        <select name="data[Key][length]" id="KeyLength" disabled="disabled" class="fluid">
                            <option value="1024">1024</option>
                            <option value="2048">2048</option>
                            <option value="3072" selected="selected">3072</option>
                            <option value="4096">4096</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row last">
                <div class="input-wrapper">
                    <a href="<?= Router::url($stepInfo['previous'], true); ?>" class="button cancel medium"><?= __('Cancel'); ?></a>
                    <button type="submit" id="next" class="button primary next medium"><?= __('Next'); ?> </button>
                </div>
            </div>
        </div>
    </div>
    <?= $this->Form->end(); ?>
</div>
