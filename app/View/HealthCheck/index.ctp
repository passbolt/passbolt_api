<?php
/**
 * Status Page
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
    $this->assign('title', __('Passbolt - The simple password management system'));
    $this->Html->css('main.min', null, array('inline' => false));
    $this->Html->css('check', null, array('inline' => false));
    $this->assign('page_classes', 'status');

/**
 * HealthcheckHtmlHelper
 * Shenanigans to reuse outputs from app/Console/HealtcheckTask.php
 */
    App::uses('AppShell', 'Console/Command');
    App::uses('HealthcheckTask', 'Console/Command/Task');
    class HealthcheckHtmlHelper extends HealthcheckTask {
        public function __construct() {
        }
        protected function assert($condition, $success, $error, $help = null) {
            if ($condition) {
                echo '<div class="message success">' . $success . '</div>' . PHP_EOL;
            } else {
                echo '<div class="message error">' . $error . '</div>' . PHP_EOL;
            }
        }
        protected function warn($condition, $success, $warning, $help = null) {
            if ($condition) {
                echo '<div class="message success">' . $success . '</div>' . PHP_EOL;
            } else {
                echo '<div class="message warning">' . $warning . '</div>' . PHP_EOL;
            }
        }
        protected function title($title) {
            echo '<h3>' . $title . '</h3>' . PHP_EOL;
        }
    }
$healtcheck = new HealthcheckHtmlHelper();
?>
<?php
    // Header block starts
    $this->start('header');
?>
<header>
	<div class="header first ">
		<?php echo $this->element('private/topNavigation'); ?>
	</div>
</header>
<?php
    $this->end('header');
    // Header block ends
?>
<div class="grid grid-responsive-12">
<div class="row">
<div class="col8">
<h2><?php echo __('Passbolt API Status') ?></h2>
<?php
    $healtcheck->assertEnvironment($checks);
?>
    <!-- if the javascript does not load this message will be shown -->
    <div id="url-rewriting-warning" class="message error">
        <?php echo __('URL rewriting is not properly configured on your server.'); ?>
        <a target="_blank" href="http://book.cakephp.org/2.0/en/installation/url-rewriting.html">Learn more.</a>
    </div>
<?php
    if ($checks['ssl']):
        echo '<div class="message success">' . __('SSL access is enabled.') . '</div>';
    else:
        echo '<div class="message error">' . __('SSL access is not enabled.') . '</div>';
    endif;
?>
<?php
    $healtcheck->assertConfigFiles($checks);
    $healtcheck->assertCore($checks);
    $healtcheck->assertDatabase($checks);
    $healtcheck->assertGpg($checks);
    $healtcheck->assertApplication($checks);
    $healtcheck->assertDevTools($checks);
?>
</div>
<div class="col4 last" style="margin-top:2.8em;">
	<h3>What is this page?</h3>
	<p>
		This page is available to help administrators diagnose if something is wrong
		with a passbolt installation and help keeping it secure.
	</p>
	<p>
        It is also possible to perform a healt check using the command line tools as follow:
    </p>
    <code>
        <pre>
./app/Console/cake passbolt healthcheck
        </pre>
    </code>
    <h3>Something wrong?</h3>
    <p>Hang in there! You can find more information on how to install and update passbolt
        in the official online help.</p>
	<a href="https://www.passbolt.com/help/" target="_blank" class="button primary big">
		<i class="fa fa-fw fa-life-saver"></i>
		Help
	</a>
</div>
</div>
</div>