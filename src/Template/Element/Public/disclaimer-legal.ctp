            <div class="message-wrapper">
                <p class="message error"><strong><?= __('Disclaimer:'); ?></strong>
                    <?= __('Please use a disposable email address.'); ?>

                    <?= __('Do not use your real email address if you are not confortable with other testers being able to see it.'); ?>

                </p>
                <p class="message warning"><strong><?= __('Warning:'); ?></strong>
                    <?= __('Demo data will be deleted periodically.') ?>

                    <?= __('This is a demo instance of passbolt for trial purposes only.'); ?>

                    <?= __('Do not use it to store sensitive information.'); ?>

                </p>
            </div>
            <p>
                <?php
                echo __('By signing up, you agree to the {0}Terms of Service{2} and {1}Privacy Policy{2}, including our use of Cookies.',
                    '<a href="https://www.passbolt.com/terms">', '<a href="https://www.passbolt.com/privacy">','</a>'); ?>

            </p>
