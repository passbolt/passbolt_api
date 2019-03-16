<?php
use Cake\Routing\Router;
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
                            <?= $this->Form->control('type', [
                                'type' => 'select',
                                'options' => ['mysql' => 'MySQL / MariaDB'],
                                'default' => 'mysql',
                                'class' => 'required fluid'
                            ]); ?>
                            <?= $this->Form->control('host', [
                                'type' => 'text',
                                'required' => 'required',
                                'placeholder' => __('host name or ip address'),
                                'label' => __('Host'),
                                'class' => 'required fluid'
                            ]); ?>

                            <?= $this->Form->control('port', [
                                'type' => 'text',
                                'required' => 'required',
                                'placeholder' => __('port'),
                                'label' => __('Port'),
                                'class' => 'required fluid',
                                'default' => '3306',
                            ]); ?>

                            <?= $this->Form->control('username', [
                                'type' => 'text',
                                'required' => 'required',
                                'placeholder' => __('username'),
                                'label' => __('Username'),
                                'class' => 'required fluid',
                            ]); ?>

                            <?= $this->Form->control('password', [
                                'type' => 'password',
                                'placeholder' => __('password'),
                                'label' => __('Password'),
                                'class' => 'fluid',
                                'type' => 'password',
                            ]); ?>

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
                    <h3>Existing database?</h3>
                    <p>If you are configuring passbolt for an existing database, the data will be kept and the schema will be migrated to the current version.</p>
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
