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
	$this->Html->css('login', null, array('block' => 'css'));
	$this->Html->script('lib/jquery/jquery-1.8.3.js', array('inline' => false, 'block'=>'scriptHeader'));
	$this->Html->script('pages/login.js', array('inline' => false, 'block'=>'scriptHeader'));
?>
<div class="grid">
	<div class="row">
		<div class="col6 push1 information">
			<h2>Welcome back!</h2>
			<div class="plugin-check-wrapper">
				<div class="plugin-check firefox error">
					<p class="message">An add-on is required to use Passbolt. Download it at <a href="https://github.com/passbolt/passbolt_ff/raw/develop/passbolt-firefox-addon.xpi">addons.mozilla.org</a>.</p>
				</div>
			</div>
			<div class="plugin-check-wrapper">
				<div class="plugin-check firefox success">
					<p class="message">Nice one! Firefox plugin is installed and up to date. You are good to go!.</p>
				</div>
			</div>
			<p>
				Passbolt is a simple password manager that allows you to easily share secrets with your team without making compromises on security!
				<a href="#">learn more</a>.
			</p>
		</div>
		<div class="col4 push1 last">
			<div class="logo">
				<h1><a href="#"><span>Passbolt</span></a></h1>
			</div>
			<div class="users login form">
			<?php echo $this->MyForm->create('User');?>
				<fieldset>
					<legend><?php echo __('Please enter your username and password'); ?></legend>
					<?php echo $this->MyForm->input('User.username', array('label' => __('Username'), 'class' =>'required fluid')); ?>
					<?php echo $this->MyForm->input('User.password', array('label' => __('Password'), 'class' =>'required fluid')); ?>
				</fieldset>
				<div class="actions-wrapper">
					<div class="submit"><input class="button primary" value="login" type="submit"></div>
					<!--a class="secondary" href="#">forgot password?</a-->
				</div>
				<?php echo $this->PassboltAuth->get(); ?>
			<?php echo $this->MyForm->end();?>
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
