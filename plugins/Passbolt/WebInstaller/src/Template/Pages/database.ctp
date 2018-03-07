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
    <?= $this->Form->create($databaseConfigurationForm); ?>
    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col7">
                    <div class="row">
                        <div class="col12">
                            <h3><?= __('Database configuration'); ?></h3>
                            <?= $this->Flash->render() ?>
                            <div class="input text required">
                                <label for="DbType">Type</label>
                                <?php
                                    echo $this->Form->select(
                                        'type',
                                        ['mysql' => 'MySQL / MariaDB'],
                                        ['default' => 'mysql', 'class' => 'required fluid']
                                    );
                                ?>
                            </div>
                            <?php
                            echo $this->Form->input('host',
                                [
                                    'required' => 'required',
                                    'placeholder' => __('host name or ip address'),
                                    'label' => __('Host'),
                                    'class' => 'required fluid'
                                ]
                            );

                            echo $this->Form->input('port',
                                [
                                    'required' => 'required',
                                    'placeholder' => __('port'),
                                    'label' => __('Port'),
                                    'class' => 'required fluid',
                                    'default' => '3306',
                                ]
                            );

                            echo $this->Form->input('username',
                                [
                                    'required' => 'required',
                                    'placeholder' => __('username'),
                                    'label' => __('Username'),
                                    'class' => 'required fluid',
                                ]
                            );

                            echo $this->Form->input('password',
                                [
                                    'required' => 'required',
                                    'placeholder' => __('password'),
                                    'label' => __('Password'),
                                    'class' => 'required fluid',
                                    'type' => 'password',
                                ]
                            );

                            echo $this->Form->input('database',
                                [
                                    'required' => 'required',
                                    'placeholder' => __('database name'),
                                    'label' => __('Database name'),
                                    'class' => 'required fluid',
                                ]
                            );
                            ?>
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
