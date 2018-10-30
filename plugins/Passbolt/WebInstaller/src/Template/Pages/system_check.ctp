<?php
use Cake\Routing\Router;
use Passbolt\WebInstaller\View\Helper\HealthcheckHtmlHelper;

$healtcheck = new HealthcheckHtmlHelper();
?>
<?= $this->element('header', ['title' => __('Welcome to Passbolt Pro! Let\'s get started with the configuration.')]) ?>
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
                    if ($data['system_ok']) :
                    ?>
                    <h2><?php echo __('Nice one! Your environment is ready for passbolt.'); ?></h2>
                    <?php
                    else:
                    ?>
                    <h2><?php echo __('Oops!! Passbolt cannot run yet on your server.'); ?></h2>
                    <?php
                    endif;
                    ?>
                    <?= $this->Flash->render() ?>
                    <?php
                    if ($data['system_ok']) {
                        echo '<div class="message success">' . __( 'Environment is configured correctly.' ) . '</div>';
                    }
                    else {
                        $healtcheck->assertEnvironment( $data );
                    }
                    ?>
                    <!-- if the javascript does not load this message will be shown -->
                    <div id="url-rewriting-warning" class="message error">
                        <?php echo __('URL rewriting is not properly configured on your server.'); ?>
                        <a target="_blank" rel="noopener" href="http://book.cakephp.org/2.0/en/installation/url-rewriting.html">Learn more.</a>
                    </div>

                    <?php
                    if ($data['system_ok']) {
                        echo '<div class="message success">' . __( 'GPG is configured correctly.' ) . '</div>';
                    }
                    else {
                        echo '<h3>' . __('GPG Configuration') . '</h3>';
                        $healtcheck->assertGpgEnv( $data );
                    }
                    ?>

                    <?php
                    if (!$data['system_ok']) :
                    ?>
                    <h3><?php echo __('SSL'); ?></h3>
                    <?php
                    endif;
                    ?>
                    <?php
                    if (isset($data['ssl']) && $data['ssl']['is'] === true):
                        echo '<div class="message success">' . __('SSL access is enabled.') . '</div>';
                    else:
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
                    if (isset($data['system_ok']) && $data['system_ok'] === true):
                    ?>
                    <a href="<?= $nextStepUrl ?>" class="button primary next big"><?= __('Start configuration') ?></a>
                    <?php else: ?>
                    <a href="#" onclick="javascript:location.reload(); return false;" class="button primary next big">Retry</a>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
