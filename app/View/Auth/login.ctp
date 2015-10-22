<?php
/**
 * Login Form View (for guest role)
 *
 * @copyright	copyright 2012 passbolt.com
 * @license		http://www.passbolt.com/license
 * @package		app.View.Users.login
 * @since		version 2.12.9
 */
	$this->assign('title',	__('Login'));
	$this->assign('page_classes','login');
	$this->Html->css('login', null, array('block' => 'css'));
	$this->Html->script('lib/jquery/dist/jquery.js', array('inline' => false, 'block'=>'scriptHeader'));
	$this->Html->script('pages/login.js', array('inline' => false, 'block'=>'scriptHeader'));
?>
<div class="grid">
	<div class="row js_main-login-section">
		<div class="col6 push1 information">
			<h2>
				<?php echo __('Download the plugin to get started!'); ?>
			</h2>
			<div class="plugin-check-wrapper">
				<div class="plugin-check firefox error">
					<p class="message">
						<?php echo __('An add-on is required to use Passbolt. Download it at: '); ?>
						<a href="https://github.com/passbolt/passbolt_ff/raw/develop/passbolt-firefox-addon.xpi">addons.mozilla.org</a>.</p>
				</div>
			</div>
			<div class="plugin-check-wrapper">
				<div class="plugin-check firefox warning">
					<p class="message">
						<?php echo __('Firefox plugin is installed but not configured.'); ?>
<?php if(Configure::read('App.registration.public')) : ?>
						<a href="register"><?php echo __('Please register!');?></a>
<?php else : ?>
						<a href="register"><?php echo __('Please contact your domain administrator to request an invitation.');?></a>
<?php endif; ?>
					</p>
				</div>
			</div>
			<?php echo $this->element('public/teaser-text'); ?>
		</div>
		<div class="col4 push1 last">
			<?php echo $this->element('public/logo'); ?>
			<div class="users login form">
				<div class="feedback">
					<i class="icon huge download" ></i>
				</div>
				<div class="actions-wrapper center">
					<a class="button primary big" href="https://github.com/passbolt/passbolt_ff/raw/develop/passbolt-firefox-addon.xpi">Download the plugin</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col3 push1 github-block">
			<?php echo $this->element('public/box-open-source'); ?>
		</div>
		<div class="col3 chrome-plugin-block">
			<?php echo $this->element('public/box-chrome-extension'); ?>
		</div>
		<div class="col4 donate-block push1 last">
			<?php echo $this->element('public/box-donate'); ?>
		</div>
	</div>
</div>
