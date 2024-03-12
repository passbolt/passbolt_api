<?php
declare(strict_types=1);

/**
 * @var \App\View\AppView $this
 * @var bool $isSystemOk
 * @var string $nextStepUrl
 * @var \Cake\Collection\Collection $resultCollection
 */

use App\Service\Healthcheck\HealthcheckServiceCollector;
use Passbolt\WebInstaller\View\Helper\HealthcheckHtmlHelper;

$healthcheckHelper = new HealthcheckHtmlHelper();
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
                    if ($isSystemOk) :
                        echo '<h2>' . __('Nice one! Your environment is ready for passbolt.') . '</h2>';
                    else :
                        echo '<h2>' . __('Oops!! Passbolt cannot run yet on your server.') . '</h2>';
                    endif;
                    ?>

                    <?= $this->Flash->render() ?>

                    <?php
                    if ($isSystemOk) {
                        echo '<div class="message success">' . __('Environment is configured correctly.') . '</div>';
                        echo '<div class="message success">' . __('GPG is configured correctly.') . '</div>';
                        echo '<div class="message success">' . __('SSL access is enabled.') . '</div>';
                    } else {
                        $resultsGroupByDomain = $resultCollection->groupBy(function ($result) {
                            return $result->domain();
                        });

                        foreach ($resultsGroupByDomain as $domain => $checkResults) {
                            echo '<h3>' . HealthcheckServiceCollector::getTitleFromDomain($domain) . '</h3>';

                            foreach ($checkResults as $checkResult) {
                                $healthcheckHelper->render($checkResult);
                            }
                        }
                    }
                    ?>

                    <!-- if the javascript does not load this message will be shown -->
                    <div id="url-rewriting-warning" class="message error">
                        <?php echo __('URL rewriting is not properly configured on your server.'); ?>
                        <a target="_blank" rel="noopener noreferrer" href="https://book.cakephp.org/4/en/installation.html#url-rewriting">Learn more.</a>
                    </div>
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
