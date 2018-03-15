<?php
use Cake\Routing\Router;
$this->Html->script('jquery-3.3.1.min.js', ['block' => 'scriptBottom']);
$this->Html->script('Passbolt/WebInstaller.email', ['block' => 'scriptBottom']);
?>
<?= $this->element('header', ['title' => __('Enter your SMTP server settings.')]) ?>
<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <?= $this->element('navigation', ['selectedSection' => 'emails']) ?>
    </div>
    <!-- main -->
    <?= $this->Form->create($emailConfigurationForm); ?>
    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col7">
                    <div class="row">
                        <div class="col12">
                            <h3><?= __('Email configuration'); ?></h3>
                            <?php
                            echo $this->Form->input('sender_name',
                                [
                                    'required' => 'required',
                                    'placeholder' => __('admin or company name'),
                                    'label' => __('Sender name'),
                                    'class' => 'required fluid',
                                ]
                            );

                            echo $this->Form->input('sender_email',
                                [
                                    'required' => 'required',
                                    'placeholder' => __('email@company.com'),
                                    'label' => __('Sender email'),
                                    'class' => 'required fluid',
                                    'type' => 'email',
                                ]
                            );
                            ?>

                            <h3><?= __('SMTP server configuration'); ?></h3>
                            <?= $this->Flash->render() ?>
                            <?php
                            echo $this->Form->input('host',
                                [
                                    'required' => 'required',
                                    'placeholder' => __('host name or ip address'),
                                    'label' => __('SMTP host'),
                                    'class' => 'required fluid'
                                ]
                            );
                            ?>
                            <div class="input text required">
                                <label for="DbType"><?= __('Use TLS?'); ?></label>
                                <?php
                                echo $this->Form->select(
                                    'tls',
                                    ['1' => 'Yes', '0' => 'No'],
                                    ['default' => '1', 'class' => 'required fluid']
                                );
                                ?>
                            </div>
                            <?php
                            echo $this->Form->input('port',
                                [
                                    'required' => 'required',
                                    'placeholder' => __('port'),
                                    'label' => __('Port'),
                                    'class' => 'required fluid',
                                    'default' => '587',
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
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col5 last">
                    <p>&nbsp;</p>
                    <div class="message warning">
                        <strong>Pro tip: a cron job is required</strong><br>
                        Once your installation is complete, do not forget to set a cron job in order to have your emails sent automatically. <br><a href="https://help.passbolt.com/hosting/install" rel="noopener" target="_blank">Read the doc</a>
                    </div>
                    <h3>Why do I need a SMTP server?</h3>
                    <p>Passbolt needs an smtp server in order to send invitation emails after an account creation and to send email notifications.</p>
<!--                    <p>You can find configuration examples for some of the most popular email providers in our <a href="https://help.passbolt.com" target="_blank" rel="noopener">knowledge base</a></p>-->

                    <h3>Send test email</h3>
                    <p>Test your configuration by sending a test email.</p>

                    <?php if(isset($test_email_status)): ?>
                        <?php if($test_email_status == true): ?>
                            <div class="message success">
                                <?=  __('The test email has been sent successfully!') ?>
                            </div>
                            <?php else: ?>
                        <div class="message error">
                            <?= __('Email could not be sent:') ?>
                            <strong><?= $test_email_error ?></strong>
                            <?php if(!empty($test_email_trace)): ?>
                            <br/>
                            <a href="#" class="see-trace"><?= __('See trace') ?></a>
                            <div class="trace hidden">
                                <?php
                                    foreach($test_email_trace as $trace_entry) {
                                        echo "<strong>" . $trace_entry['cmd'] . '</strong><br>';
                                        if (!empty($trace_entry['response'])) {
                                            foreach($trace_entry['response'] as $response) {
                                                echo "[{$response['code']}] {$response['message']}<br>";
                                            }
                                        }
                                    }
                                ?>
                            </div>
                            <?php endif; ?>
                        </div>
                            <?php endif; ?>
                    <?php endif; ?>
                    <div class="input text required">
                        <?php
                        echo $this->Form->input('email_test_to',
                            [
                                'placeholder' => __('Your email address'),
                                'label' => false,
                                'class' => 'required fluid',
                                'type' => 'email',
                            ]
                        );
                        ?>
                        <input type="submit" name="send_test_email" class="button" value="<?= __('Send test email'); ?>" >
                    </div>
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
