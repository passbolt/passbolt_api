<div class="sidebar-help transparent">
    <h3><?php echo __('Send test email'); ?></h3>
    <p>Test your configuration by sending a test email.</p>

    <?php if (isset($test_email_status)) : ?>
        <?php if ($test_email_status == true) : ?>
            <div class="message success">
                <?=  __('The test email has been sent successfully!') ?>
            </div>
        <?php else : ?>
            <div class="message error">
                <?= __('Email could not be sent:') ?>
                <strong><?= $test_email_error ?></strong>
                <?php if (!empty($test_email_trace)) : ?>
                    <br/>
                    <a href="#" class="see-trace"><?= __('See trace') ?></a>
                    <div class="trace hidden">
                        <?php
                        foreach ($test_email_trace as $trace_entry) {
                            echo '<strong>' . $trace_entry['cmd'] . '</strong><br>';
                            if (!empty($trace_entry['response'])) {
                                foreach ($trace_entry['response'] as $response) {
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
        <input type="submit" name="send_test_email" class="button medium" value="<?= __('Send test email'); ?>" >
    </div>
</div>
