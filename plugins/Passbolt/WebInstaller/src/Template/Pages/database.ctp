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
    <?= $this->Form->create(); ?>
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

                            echo $this->Form->input('name',
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
                </div>
            </div>
            <div class="row last">
                <div class="input-wrapper">
                    <a href="<?= Router::url('install/system_check'); ?>" class="button cancel big"><?= __('Cancel'); ?></a>
                    <input type="submit" class="button primary next big" value="<?= __('Next'); ?>">
                </div>
            </div>
        </div>
    </div>
    <?= $this->Form->end(); ?>
</div>
