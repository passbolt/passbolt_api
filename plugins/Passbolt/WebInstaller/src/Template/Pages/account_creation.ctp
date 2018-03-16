<?= $this->element('header', ['title' => __('Installation is done. Create your user account!')]) ?>
<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <?= $this->element('navigation', ['selectedSection' => 'first_user']) ?>
    </div>
    <!-- main -->
    <?= $this->Form->create($user); ?>
    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col7">
                    <div class="row">
                        <div class="col12">
                            <h3><?= __('Admin user details'); ?></h3>
                            <?= $this->Flash->render() ?>
                            <?php
                            echo $this->Form->control('profile.first_name',
                                [
                                    'required' => 'required',
                                    'placeholder' => __('First name'),
                                    'label' => __('First name'),
                                    'class' => 'required fluid',
                                ]
                            );
                            ?>
                            <?php
                            echo $this->Form->control('profile.last_name',
                                [
                                    'required' => 'required',
                                    'placeholder' => __('Last name'),
                                    'label' => __('Last name'),
                                    'class' => 'required fluid',
                                ]
                            );
                            ?>
                            <?php
                            echo $this->Form->control('username',
                                [
                                    'required' => 'required',
                                    'type' => 'email',
                                    'placeholder' => __('mail@yourdomain.com'),
                                    'label' => __('Username'),
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
                    <input type="submit" class="button primary next big" value="<?= __('Next'); ?>">
                </div>
            </div>
        </div>
    </div>
    <?= $this->Form->end(); ?>
</div>
