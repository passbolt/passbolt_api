<?php
use Cake\Routing\Router;
$this->Html->script('vendors/jquery.min.js', ['block' => 'scriptBottom']);
$this->Html->script('vendors/chosen.jquery.js', ['block' => 'scriptBottom']);
$this->Html->script('web_installer/database', ['block' => 'scriptBottom']);
?>
<?= $this->element('header', ['title' => __('Enter your database details.')]) ?>
<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <?= $this->element('navigation', ['selectedSection' => 'database']) ?>
    </div>
    <!-- main -->
    <?= $this->Form->create($formExecuteResult); ?>
    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col7">
                    <div class="row">
                        <div class="col12">
                            <h3><?= __('Database configuration'); ?></h3>
                            <?= $this->Flash->render() ?>
                            <?php
                            if (isset($stepInfo['defaultConfig'])):
                            ?>
                                <div class="message notice">
                                    <p><?php echo __('An existing database has been detected on your server. The connection details are pre-filled below.') ?><br>
                                        <?php echo __('You can keep it as it is (default), or replace it with the details of another database.') ?>
                                    </p>
                                </div>
                            <?php
                            endif;
                            ?>
                            <div class="singleline connection_info protocol_host_port clearfix required">
                                <label><?php echo __('Database connection url'); ?></label>
                                <div class="input text field_protocol_host">
                                    <?= $this->Form->control('type', [
                                        'type' => 'select',
                                        'options' => ['mysql' => 'mysql://'],
                                        'default' => 'mysql',
                                        'templates' => [
                                            'inputContainer' => '<div class="input text protocol">{{content}}</div>'
                                        ],
                                        'label' => false,
                                        'class' => 'required fluid'
                                    ]); ?>
                                    <?= $this->Form->control('host', [
                                        'type' => 'text',
                                        'templates' => [
                                            'inputContainer' => '<div class="input text host">{{content}}</div>'
                                        ],
                                        'placeholder' => __('host name or ip'),
                                        'label' => false,
                                        'class' => 'required fluid'
                                    ]); ?>
                                </div>
                                <?= $this->Form->control('port', [
                                    'type' => 'number',
                                    'templates' => [
                                        'inputContainer' => '<div class="input text port">{{content}}</div>'
                                    ],
                                    'required' => 'required',
                                    'placeholder' => __('3306'),
                                    'label' => false,
                                    'class' => 'required fluid',
                                    'default' => '3306',

                                ]); ?>
                            </div>

                            <div class="singleline clearfix">
                                <?= $this->Form->control('username', [
                                    'templates' => [
                                        'inputContainer' => '<div class="input text first-field required">{{content}}</div>'
                                    ],
                                    'type' => 'text',
                                    'required' => 'required',
                                    'placeholder' => __('username'),
                                    'label' => __('Username'),
                                    'class' => 'required fluid',
                                ]); ?>

                                <?= $this->Form->control('password', [
                                    'templates' => [
                                        'inputContainer' => '<div class="input text last-field required">{{content}}</div>'
                                    ],
                                    'type' => 'password',
                                    'placeholder' => __('password'),
                                    'label' => __('Password'),
                                    'class' => 'fluid',
                                ]); ?>

                            </div>
                            <?= $this->Form->control('database', [
                                'type' => 'text',
                                'required' => 'required',
                                'placeholder' => __('database name'),
                                'label' => __('Database name'),
                                'class' => 'required fluid',
                            ]); ?>
                        </div>
                    </div>
                </div>
                <div class="col5 last">
                    <h3>Existing installation?</h3>
                    <p>If you want to use an existing passbolt database, the installer will take care of updating it to the current passbolt version while keeping your data.</p>
                    <p>As a precaution, do not forget to backup your database before you continue.</p>
                </div>
            </div>
            <div class="row last">
                <div class="input-wrapper">
                    <a href="<?= Router::url($stepInfo['previous'], true); ?>" class="button cancel big"><?= __('Cancel'); ?></a>
                    <input type="submit" class="button primary next big" value="<?= __('Next'); ?>">
                </div>
            </div>
        </div>
    </div>
    <?= $this->Form->end(); ?>
</div>
