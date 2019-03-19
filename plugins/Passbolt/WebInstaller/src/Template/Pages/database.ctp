<?php
use Cake\Routing\Router;
?>
<?php echo $this->element('header', ['title' => __('Enter your database details.')]) ?>
<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <?php echo $this->element('navigation', ['selectedSection' => 'database']) ?>
    </div>
    <!-- main -->
    <?php echo $this->Form->create($formExecuteResult); ?>
    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col7">
                    <div class="row">
                        <div class="col12">
                            <h3><?php echo __('Database configuration'); ?></h3>
                            <?php echo $this->Flash->render() ?>
                            <?php echo $this->Form->control('type', [
                                'type' => 'select',
                                'options' => ['mysql' => 'MySQL / MariaDB'],
                                'default' => 'mysql',
                                'class' => 'required fluid'
                            ]); ?>
                            <?php echo $this->Form->control('host', [
                                'type' => 'text',
                                'required' => 'required',
                                'placeholder' => __('host name or ip address'),
                                'label' => __('Host'),
                                'class' => 'required fluid'
                            ]); ?>

                            <?php echo $this->Form->control('port', [
                                'type' => 'text',
                                'required' => 'required',
                                'placeholder' => __('port'),
                                'label' => __('Port'),
                                'class' => 'required fluid',
                                'default' => '3306',
                            ]); ?>

                            <?php echo $this->Form->control('username', [
                                'type' => 'text',
                                'required' => 'required',
                                'placeholder' => __('username'),
                                'label' => __('Username'),
                                'class' => 'required fluid',
                            ]); ?>

                            <?php echo $this->Form->control('password', [
                                'type' => 'password',
                                'placeholder' => __('password'),
                                'label' => __('Password'),
                                'class' => 'fluid',
                                'type' => 'password',
                            ]); ?>

                            <?php echo $this->Form->control('database', [
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
                    <a href="<?php echo Router::url($stepInfo['previous'], true); ?>" class="button cancel big"><?php echo __('Cancel'); ?></a>
                    <input type="submit" class="button primary next big" value="<?php echo __('Next'); ?>">
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
