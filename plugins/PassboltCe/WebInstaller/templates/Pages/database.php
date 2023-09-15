<?php

/**
 * @var \App\View\AppView $this
 */

use Cake\Database\Driver\Mysql;
use Cake\Database\Driver\Postgres;
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
                            <h2><?= __('Database configuration'); ?></h2>
                            <?= $this->Flash->render() ?>
                            <?php
                            if (isset($stepInfo['defaultConfig'])) :
                                ?>
                                <div class="message notice">
                                    <p><?php echo __('An existing database has been detected on your server. The connection details are pre-filled below.') ?><br>
                                        <?php echo __('You can keep it as it is (default), or replace it with the details of another database.') ?>
                                    </p>
                                </div>
                                <?php
                            endif;
                            ?>
                            <div class="clearfix required">
                                <label><?php echo __('Database connection url'); ?></label>
                                <div class="input text singleline connection_info">
                                    <?= $this->Form->control('driver', [
                                        'type' => 'select',
                                        'options' => [
                                            Mysql::class => 'mysql://',
                                            Postgres::class => 'postgresql://',
                                        ],
                                        'default' => Mysql::class,
                                        'templates' => [
                                            'inputContainer' => '<div class="input text protocol">{{content}}</div>',
                                        ],
                                        'label' => false,
                                        'class' => 'required fluid',
                                    ]); ?>
                                    <?= $this->Form->control('host', [
                                        'type' => 'text',
                                        'templates' => [
                                            'inputContainer' => '<div class="input text host">{{content}}</div>',
                                        ],
                                        'placeholder' => __('host name or ip address'),
                                        'label' => false,
                                        'class' => 'required fluid',
                                    ]); ?>
                                    <?= $this->Form->control('port', [
                                        'type' => 'number',
                                        'templates' => [
                                            'inputContainer' => '<div class="input text port">{{content}}</div>',
                                        ],
                                        'required' => 'required',
                                        'placeholder' => '3306',
                                        'label' => false,
                                        'class' => 'required fluid',
                                        'default' => '3306',

                                    ]); ?>
                                </div>
                            </div>

                            <div class="singleline clearfix">
                                <?= $this->Form->control('username', [
                                    'templates' => [
                                        'inputContainer' => '<div class="input text first-field required">{{content}}</div>',
                                    ],
                                    'type' => 'text',
                                    'required' => 'required',
                                    'placeholder' => __('username'),
                                    'label' => __('Username'),
                                    'class' => 'required fluid',
                                ]); ?>

                                <?= $this->Form->control('password', [
                                    'templates' => [
                                        'inputContainer' => '<div class="input text last-field required">{{content}}</div>',
                                    ],
                                    'type' => 'password',
                                    'placeholder' => __('password'),
                                    'label' => __('Password'),
                                    'class' => 'fluid',
                                ]); ?>
                            </div>
                            <div class="singleline clearfix">
                                <?= $this->Form->control('database', [
                                    'type' => 'text',
                                    'required' => 'required',
                                    'placeholder' => __('database name'),
                                    'label' => __('Database name'),
                                    'class' => 'required fluid',
                                ]); ?>
                                <?= $this->Form->control('schema', [
                                    'templates' => [
                                        'inputContainer' => '<div class="input text last-field required" id="schema-block">{{content}}</div>',
                                    ],
                                    'type' => 'text',
                                    'required' => true,
                                    'placeholder' => __('schema'),
                                    'label' => __('Schema'),
                                    'class' => 'fluid',
                                    'default' => 'public',
                                ]); ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col5 last">
                    <?= $this->element('sidebar/existing_installation') ?>
                </div>
            </div>
            <div class="row last">
                <div class="input-wrapper">
                    <a href="<?= Router::url($stepInfo['previous'], true); ?>" class="button cancel medium"><?= __('Cancel'); ?></a>
                    <input type="submit" class="button primary next medium" value="<?= __('Next'); ?>">
                </div>
            </div>
        </div>
    </div>
    <?= $this->Form->end(); ?>
</div>
