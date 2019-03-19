<?php
use Cake\Routing\Router;
$this->Html->script('jquery-3.3.1.min.js', ['block' => 'scriptBottom']);
$this->Html->script('web_installer/email', ['block' => 'scriptBottom']);
?>
<?php echo $this->element('header', ['title' => __('Enter your SMTP server settings.')]) ?>
<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <?php echo $this->element('navigation', ['selectedSection' => 'emails']) ?>
    </div>
    <!-- main -->
    <?php echo $this->Form->create($formExecuteResult); ?>
    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col7">
                    <div class="row">
                        <div class="col12">
                            <h3><?php echo __('Email configuration'); ?></h3>
                            <?php echo $this->Flash->render() ?>
                            <?php echo $this->Form->control('sender_name', [
                                'type' => 'text',
                                'required' => 'required',
                                'placeholder' => __('admin or company name'),
                                'label' => __('Sender name'),
                                'class' => 'required fluid',
                            ]); ?>
                            <?php echo $this->Form->control('sender_email', [
                                'type' => 'email',
                                'required' => 'required',
                                'placeholder' => __('email@company.com'),
                                'label' => __('Sender email'),
                                'class' => 'required fluid',
                                'type' => 'email',
                            ]); ?>

                            <h3><?php echo __('SMTP server configuration'); ?></h3>
                            <?php echo $this->Form->control('host', [
                                'type' => 'text',
                                'required' => 'required',
                                'placeholder' => __('host name or ip address'),
                                'label' => __('SMTP host'),
                                'class' => 'required fluid'
                            ]); ?>
                            <div class="input text required">
                                <label for="tls"><?php echo __('Use TLS?'); ?></label>
                                <?php echo $this->Form->control('tls', [
                                    'options' => ['1' => 'Yes', '0' => 'No'],
                                    'default' => '1',
                                    'class' => 'required fluid'
                                ]); ?>
                            </div>
                            <?php echo $this->Form->control('port', [
                                'type' => 'text',
                                'required' => 'required',
                                'placeholder' => __('port'),
                                'label' => __('Port'),
                                'class' => 'required fluid',
                                'default' => '587']); ?>
                            <?php echo $this->Form->control('username', [
                                'type' => 'text',
                                'placeholder' => __('username'),
                                'label' => __('Username'),
                                'class' => 'fluid',
                            ]); ?>
                            <?php echo $this->Form->control('password', [
                                'placeholder' => __('password'),
                                'label' => __('Password'),
                                'class' => 'fluid',
                                'type' => 'password',
                            ]); ?>
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

                    <h3>Send test email</h3>
                    <p>Test your configuration by sending a test email.</p>

                    <?php if(isset($test_email_status)): ?>
                        <?php if($test_email_status == true): ?>
                            <div class="message success">
                                <?php echo  __('The test email has been sent successfully!') ?>
                            </div>
                            <?php else: ?>
                        <div class="message error">
                            <?php echo __('Email could not be sent:') ?>
                            <strong><?php echo $test_email_error ?></strong>
                            <?php if(!empty($test_email_trace)): ?>
                            <br/>
                            <a href="#" class="see-trace"><?php echo __('See trace') ?></a>
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
                        echo $this->Form->control('email_test_to', [
                            'placeholder' => __('Your email address'),
                            'label' => false,
                            'class' => 'required fluid',
                            'type' => 'email',
                        ]);
                        ?>
                        <input type="submit" name="send_test_email" class="button" value="<?php echo __('Send test email'); ?>" >
                    </div>
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
