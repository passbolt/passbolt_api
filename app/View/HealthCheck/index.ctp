<?php
/**
 * Status Page
 *
 * @copyright (c) 2016-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
$this->assign('title', __('Passbolt - The simple password management system'));
$this->Html->css('main.min', null, array('inline' => false));
$this->Html->css('check', null, array('inline' => false));
$this->assign('page_classes', 'status');
?>
<?php $this->start('header'); ?>
<header>
	<div class="header first ">
		<?php echo $this->element('private/topNavigation'); ?>
	</div>
</header>
<?php $this->end('header'); ?>
<div class="grid grid-responsive-12">
<div class="row">
<div class="col8">
<h2><?php echo __('Passbolt API Status') ?></h2>
<h3><?php echo __('Core configuration'); ?></h3>
	<?php
	if (Configure::read('Security.salt') === 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi'):
		echo '<div class="message error">';
		echo __('Please change the value of Security.salt in core.php');
		echo '</div>';
	endif;
	if (Configure::read('Security.cipherSeed') === '76859309657453542496749683645'):
		echo '<div class="message error">';
		echo __('Please change the value of Security.cipherSeed in core.php ');
		echo '</div>';
	endif;
?>
<div id="url-rewriting-warning" class="message error">
	<?php echo __('URL rewriting is not properly configured on your server.'); ?>
	<a target="_blank" href="http://book.cakephp.org/2.0/en/installation/url-rewriting.html">Learn more.</a>
</div>
<?php 
	if ($checks['phpVersion']):
		echo '<div class="message success">';
		echo __('Your version of PHP is 5.2.8 or higher.');
		echo '</div>';
	else:
		echo '<div class="message error">';
		echo __('Your version of PHP is too low. You need PHP 5.2.8 or higher to use CakePHP.');
		echo '</div>';
	endif;
?>
<?php
	if ($checks['tmp']):
		echo '<div class="message success">';
		echo __('Your tmp directory is writable.');
		echo '</div>';
	else:
		echo '<div class="message error">';
		echo __('Your tmp directory is NOT writable.');
		echo '</div>';
	endif;
?>
<?php
	if ($checks['settings']):
		echo '<div class="message success">';
		echo __('The %s is being used for core caching. To change the config edit %s', '<em>' . $settings['engine'] . 'Engine</em>', 'core.php');
		echo '</div>';
	else:
		echo '<div class="message error">';
		echo __('Your cache is NOT working. Please check the settings in %s', 'core.php');
		echo '</div>';
	endif;
?>
<?php
	if ($checks['dbFile']):
		echo '<div class="message success">';
		echo __('Your database configuration file is present.');
		echo '</div>';
	else:
		echo '<div class="message error">';
		echo __('Your database configuration file is NOT present.');
		echo ' ' . __('Rename %s to %s', 'APP/Config/database.php.default', 'APP/Config/database.php');
		echo '</div>';
	endif;
?>
<?php
	if ($checks['dbFile']):
		if ($checks['dbConnect']):
			echo '<div class="message success">';
			echo __('Passbolt is able to connect to the database.');
			echo '</div>';
		else:
			echo '<div class="message error">';
			echo __('Passbolt is NOT able to connect to the database. Check your DB config.');
			echo '</div>';
		endif;
	endif;
?>
<?php
	if (!$checks['validation']):
		echo '<div class="message error">';
		echo __('PCRE has not been compiled with Unicode support.');
		echo __('Recompile PCRE with Unicode support by adding <code>--enable-unicode-properties</code> when configuring');
		echo '</div>';
	endif;
?>
<?php
	if ($checks['debug']):
		echo '<div class="message error">';
		echo __('Debug mode is on.');
		echo '</div>';
	else:
		echo '<div class="message success">';
		echo __('Debug mode is off.');
		echo '</div>';
	endif;
?>
<h3><?php echo __('Application configuration'); ?></h3>
<?php
	if ($checks['ssl']):
		echo '<div class="message success">';
		echo __('SSL access is enabled.');
		echo '</div>';
	else:
		echo '<div class="message error">';
		echo __('SSL access is not enabled.');
		echo '</div>';
	endif;
?>
<?php
if ($checks['sslForce']):
	echo '<div class="message success">';
	echo __('Passbolt is configured to force SSL use.');
	echo '</div>';
else:
	echo '<div class="message error">';
	echo __('Passbot is not configured to force SSL use. <em>(App.ssl.force in app.php configuration file)</em>.');
	echo '</div>';
endif;
?>
<?php
	if ($checks['gpg']):
		echo '<div class="message success">';
		echo __('PHP GPG Module is on.');
		echo '</div>';
	else:
		echo '<div class="message error">';
		echo __('PHP GPG Module is not installed / enabled.');
		echo '</div>';
	endif;
?>
<?php
	if (!$checks['gpgKey']) {
		echo '<div class="message error">';
		echo __('GPG Key for the server was not found');
		echo '</div>';
	} else {
		if ($checks['gpgKeyDefault']) {
			echo '<div class="message success">';
			echo __('The server GPG key is not the default one.');
			echo '</div>';
		} else {
			echo '<div class="message error">';
			echo __('Do not use the default GPG key for the server.');
			echo '</div>';
		}
	}
?>
<?php
	if (!isset($checks['latestVersion'])):
		echo '<div class="message error">';
		echo __('Could not connect to passbolt repository. It is not possible check if your version is up to date.');
		echo '</div>';
	else:
		if (!$checks['latestVersion']):
			echo '<div class="message error">';
			echo __('Your install is not up to date. It should be: ') . $checks['remoteVersion'] . '. ';
			echo __('But you are using: ') . Configure::read('App.version.number');
			echo '</div>';
		else:
			echo '<div class="message success">';
			echo __('You application is up to date. You are using: ') . $checks['remoteVersion'];
			echo '</div>';
		endif;
	endif;
?>
<?php
	if ($checks['needMigration']):
		echo '<div class="message error">';
		echo __('You schema is not up to date, please run the migration scripts.');
		echo '</div>';
	else:
		echo '<div class="message success">';
		echo __('Your schema up to date.');
		echo '</div>';
	endif;
	?>
<?php
	if ($checks['selenium']):
		echo '<div class="message error">';
		echo __('Selenium API endpoints are active. This setting should be used for testing only.');
		echo '</div>';
	else:
		echo '<div class="message success">';
		echo __('Selenium API endpoints are disabled.');
		echo '</div>';
	endif;
?>
<?php
	if ($checks['registration']):
		echo '<div class="message success">';
		echo __('Registration is closed, only administrators can add users.');
		echo '</div>';
	else:
		echo '<div class="message warning">';
		echo __('Registration is open to everyone.');
		echo '</div>';
	endif;
?>
<?php
	if ($checks['adminCount']):
		echo '<div class="message success">';
		echo __('There is at least one admin account.');
		echo '</div>';
	else:
		echo '<div class="message warning">';
		echo __('There is no admin user. Create one.');
		echo '</div>';
	endif;
?>
<?php
	if ($checks['jsProd']):
		echo '<div class="message success">';
		echo __('Serving the compiled version of the javascript app.');
		echo '</div>';
	else:
		echo '<div class="message warning">';
		echo __('Non compiled JS is being used. It will be slower. Set App.js.build to production in app.php.');
		echo '</div>';
	endif;
?>
<h3><?php echo __('Development tools'); ?></h3>
<?php
	if ($checks['phpunit']):
		if ($checks['phpunitVersion']):
			echo '<div class="message success">';
			echo __('Phpunit v3.7.38 is installed.');
			echo '</div>';
		else:
			echo '<div class="message error">';
			echo __('Phpunit version does not seem right. Please use v3.7.38, as more recent version will not work.');
			echo '</div>';
		endif;
	else:
		echo '<div class="message warning">';
		echo __('Phpunit is not installed. It is useful if want to run passbolt testsuite.');
		echo '</div>';
	endif;
?>
<?php
	if ($checks['debugKit']):
		echo '<div class="message success">';
		echo __('DebugKit plugin is present');
		echo '</div>';
	else:
		echo '<div class="message warning">';
		echo __('DebugKit is not installed. It is useful if you are extending passbolt.');
		echo ' '.$this->Html->link(__('Learn more.'), 'https://github.com/cakephp/debug_kit/tree/2.2');
		echo '</div>';
	endif;
?>
</div>
<div class="col4 last" style="margin-top:2.8em;">
	<h3>What is this page?</h3>
	<p>
		This page is available to help administrators diagnose if something is wrong
		with a passbolt installation and help keeping it secure. If you want more information on how to install passbolt
		please checkout our step by step guide.
	</p>
	<a href="https://www.passbolt.com/help/" target="_blank" class="button primary big">
		<i class="fa fa-fw fa-life-saver"></i>
		Help
	</a>
</div>
</div>
</div>