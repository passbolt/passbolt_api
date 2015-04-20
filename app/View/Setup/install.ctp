<?php
/**
 * Install very first step.
 *
 * @copyright	copyright 2015 passbolt.com
 * @license		http://www.passbolt.com/license
 * @package		app.View.Setup.Install
 * @since		version 2.12.9
 */
$this->assign('title',	__('Install'));

$this->Html->script('lib/jquery/jquery-1.8.3.js', array('inline' => false, 'block'=>'scriptHeader'));
$this->Html->script('pages/install.js', array('inline' => false, 'block'=>'scriptBottom'));
?>

<div>
	<?php echo __('Welcome to passbolt! Let\'s take 5 min to setup your system.') ?>
</div>

<ul>
	<li><?php echo __('1. Get the plugin') ?></li>
	<li><?php echo __('2. Define your keys') ?></li>
	<li><?php echo __('3. Set a master password') ?></li>
	<li><?php echo __('4. Set a security token') ?></li>
	<li><?php echo __('5. Login!') ?></li>
</ul>

<div>
	<label><?php echo __('Plugin check') ?></label>
	<div>
		<?php echo __('An add-on is required to use Passbolt. Download it at addons.mozilla.org.') ?>
	</div>
</div>

<div>
	<label><?php echo __('Why do I need a plugin?') ?></label>
	<div>
		<?php echo __('Passbolt requires a browser add-on to guarantee that your secret key and your master password are never
		accessible to any website (including passbolt.com itself). This is also the only way to guarantee that
		the core cryptographic libraries cannot be tampered with.'); ?>
		<a href="http://passbolt.com" target="_blank">
			<?php echo __('Learn more >'); ?>
		</a>
	</div>

	<a id="js_plugin_check_retry" href="#"><?php echo __('Retry'); ?></a>
</div>
