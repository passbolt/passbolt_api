<?php
declare(strict_types=1);

/**
 * @var \App\View\AppView $this
 * @var bool $isSystemOk
 * @var bool $isNextMinPhpVersionPassed
 * @var bool $isRequestHttps
 * @var string $nextStepUrl
 * @var \Cake\Collection\Collection $environmentChecks
 * @var \Cake\Collection\Collection $gpgChecks
 */

use App\View\Helper\HealthcheckHtmlHelper;

$healthcheckHelper = new HealthcheckHtmlHelper();

?>
<?= $this->element('header', ['title' => __('Welcome to Passbolt! Let\'s get started with the configuration.')]) ?>
<div class="panel main ">
    <!-- wizard steps -->
    <div class="panel left">
        <?= $this->element('navigation', ['selectedSection' => 'system_check']) ?>
    </div>
    <!-- main -->
    <div class="panel middle">
        <div class="grid grid-responsive-12">
            <div class="row">
                <div class="col7">
                    <?php
                    if ($isSystemOk) :
                        echo '<h2>' . __('Nice one! Your environment is ready for passbolt.') . '</h2>';
                    else :
                        echo '<h2>' . __('Oops!! Passbolt cannot run yet on your server.') . '</h2>';
                    endif;
                    ?>

                    <?= $this->Flash->render() ?>

                    <?php
                    /**
                     * Display environment domain results.
                     */
                    // We want display the warning when php version is less than next minimum PHP version we'll support.
                    // That's why this complex condition :)
                    if (!$isSystemOk || ($isSystemOk && !$isNextMinPhpVersionPassed)) {
                        echo '<h3>Environment</h3>';
                        foreach ($environmentChecks as $checkResult) {
                            $healthcheckHelper->render($checkResult);
                        }
                    } else {
                        // Environment is fine
                        echo '<div class="message success">' . __('Environment is configured correctly.') . '</div>';
                    }
                    ?>

                    <!-- if the javascript does not load this message will be shown -->
                    <div id="url-rewriting-warning" class="message error">
                        <?php echo __('URL rewriting is not properly configured on your server.'); ?>
                        <a target="_blank" rel="noopener noreferrer" href="https://book.cakephp.org/4/en/installation.html#url-rewriting">Learn more.</a>
                    </div>

                    <?php
                    /**
                     * Display GPG results.
                     */
                    if ($isSystemOk) {
                        echo '<div class="message success">' . __('GPG is configured correctly.') . '</div>';
                    } else {
                        echo '<h3>' . __('GPG Configuration') . '</h3>';
                        foreach ($gpgChecks as $domain => $checkResult) {
                            $healthcheckHelper->render($checkResult);
                        }
                    }
                    ?>

                    <?php
                    if (!$isSystemOk) :
                        ?>
                        <h3><?php echo __('SSL'); ?></h3>
                    <?php
                    endif;
                    ?>
                    <?php
                    if ($isRequestHttps) :
                        echo '<div class="message success">' . __('SSL access is enabled.') . '</div>';
                    else :
                        echo '<div class="message warning">' . __('SSL access is not enabled. You can still proceed, but it is highly recommended that you configure your web server to use HTTPS before you continue.') . '</div>';
                    endif;
                    ?>
                </div>

                <div class="col5 last">
                    <?= $this->element('sidebar/help_box') ?>
                </div>
            </div>
            <div class="row last">
                <div class="input-wrapper">
                    <?php
                    if ($isSystemOk) :
                        ?>
                    <a href="<?= $nextStepUrl ?>" class="button primary next medium"><?= __('Start configuration') ?></a>
                    <?php else : ?>
                    <a href="#" onclick="javascript:location.reload(); return false;" class="button primary next medium">Retry</a>
                        <?php
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
