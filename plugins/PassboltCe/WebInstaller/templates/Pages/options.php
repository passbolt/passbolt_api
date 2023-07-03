<?php
use Cake\Routing\Router;
?>
<?= $this->element('header', ['title' => __('Choose your preferences.')]) ?>
<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <?= $this->element('navigation', ['selectedSection' => 'options']) ?>
    </div>
    <!-- main -->
    <?= $this->Form->create($formExecuteResult); ?>
    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col7">
                    <div class="row">
                        <div class="col12">
                            <h2><?= __('Options'); ?></h2>
                            <?= $this->Flash->render() ?>
                            <?php
                                echo $this->Form->control('full_base_url', [
                                    'type' => 'text',
                                    'required' => 'required',
                                    'placeholder' => __('Full Base Url'),
                                    'label' => __('Full base url'),
                                    'class' => 'required fluid',
                                    'templates' => [
                                        'inputContainer' => '<div class="input text required">{{content}}<div class="help-message">' . __('This is the url where passbolt will be accessible. This url will be used for places where the passbolt url cannot be guessed automatically, such as links in emails. No trailing slash.') . '</div></div>',
                                    ],
                                ]);
                                ?>
                            <div class="input text required">
                                <?php
                                    echo $this->Form->control('force_ssl', [
                                        'options' => ['1' => 'Yes', '0' => 'No'],
                                        'default' => $force_ssl ?? 0,
                                        'label' => __('Force SSL?'),
                                        'class' => 'required fluid',
                                    ]);
                                ?>
                                <div class="help-message"><?= __('Forcing SSL means that passbolt will not accept connections coming from a non secure protocol. If SSL is forced, your server has to be configured for HTTPS. It is highly recommended that you do so.') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col5 last">
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
