<?php
declare(strict_types=1);

/**
 * @var \App\View\AppView $this
 * @var array $data
 * @var string $nextStepUrl
 */

use Cake\Routing\Router;

$this->Html->script('vendors/jquery.min.js', ['block' => 'scriptBottom']);
$this->Html->script('web_installer/email', ['block' => 'scriptBottom']);
?>
<?= $this->element('header', ['title' => __('Enter your SMTP server settings.')]) ?>
<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <?= $this->element('navigation', ['selectedSection' => 'emails']) ?>
    </div>
    <!-- main -->
    <?= $this->Form->create($formExecuteResult); ?>
    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col7">
                    <div class="row">
                        <div class="col12">
                            <h2><?= __('Email configuration'); ?></h2>
                            <?= $this->Flash->render() ?>
                            <?= $this->Form->control('sender_name', [
                                'type' => 'text',
                                'required' => 'required',
                                'placeholder' => __('admin or company name'),
                                'label' => __('Sender name'),
                                'class' => 'required fluid',
                            ]); ?>
                            <?= $this->Form->control('sender_email', [
                                'type' => 'email',
                                'required' => 'required',
                                'placeholder' => __('email@company.com'),
                                'label' => __('Sender email'),
                                'class' => 'required fluid',
                                'type' => 'email',
                            ]); ?>

                            <h2><?= __('SMTP server configuration'); ?></h2>
                            <?= $this->Form->control('host', [
                                'type' => 'text',
                                'required' => 'required',
                                'placeholder' => __('host name or ip address'),
                                'label' => __('SMTP host'),
                                'class' => 'required fluid',
                            ]); ?>
                            <div class="input text required">
                                <?= $this->Form->control('tls', [
                                    'options' => ['1' => 'Yes', '0' => 'No'],
                                    'default' => '1',
                                    'label' => __('Use TLS?'),
                                    'class' => 'required fluid',
                                ]); ?>
                            </div>
                            <?= $this->Form->control('port', [
                                'type' => 'text',
                                'required' => 'required',
                                'placeholder' => __('port'),
                                'label' => __('Port'),
                                'class' => 'required fluid',
                                'default' => '587']); ?>
                            <?= $this->Form->control('authentication_method', [
                                'options' => [
                                    'username_and_password' => 'Username & password',
                                    'username_only' => 'Username only',
                                    'none' => 'None',
                                ],
                                'required' => 'required',
                                'default' => 'username_and_password',
                                'label' => __('Authentication method'),
                                'class' => 'required fluid',
                            ]); ?>
                            <div id="smtp-config-input-username">
                            <?= $this->Form->control('username', [
                                'type' => 'text',
                                'placeholder' => __('username'),
                                'label' => __('Username'),
                                'class' => 'fluid',
                            ]); ?>
                            </div>
                            <div id="smtp-config-input-password">
                                <?= $this->Form->control('password', [
                                    'placeholder' => __('password'),
                                    'label' => __('Password'),
                                    'class' => 'fluid',
                                    'type' => 'password',
                                ]); ?>
                            </div>
                            <?= $this->Form->control('client', [
                                'placeholder' => __('client'),
                                'label' => __('Client'),
                                'class' => 'fluid',
                                'type' => 'text',
                            ]); ?>
                        </div>
                    </div>
                </div>
                <div class="col5 last">
                    <p>&nbsp;</p>
                    <?= $this->element('sidebar/smtp_explanations') ?>
                    <?= $this->element('sidebar/send_test_email') ?>
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
