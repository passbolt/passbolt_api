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
	$this->Html->css('default/login', null, array('block' => 'css'));
	$this->Html->script('lib/jquery/jquery-1.8.3.js', array('inline' => false, 'block'=>'scriptHeader'));
	$this->Html->script('pages/login.js', array('inline' => false, 'block'=>'scriptHeader'));
?>
<div class="grid">
<div class="row">
	<div class="col5 push1 information">
		<h2>Welcome back!</h2>
		<p>
			Passbolt is a simple password manager that allow you to easily share passwords within your team!
			Passwords are encrypted in the browser using <a href="http://openpgpjs.org/">OpenGPG.js</a>.
			Passbolt is a zero-knowledge application, this means we will never see your password.
		</p>
	</div>
	<div class="col4 push2 last">
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
			<?php echo $this->MyForm->submit(__('login'));?>
			<span class="forgot"><a href="#">forgot password?</a></span>
			<?php echo $this->PassboltAuth->get(); ?>
		<?php echo $this->MyForm->end();?>
		</div>
	</div>
</div>
<div class="row" style="padding-top:1em;">
<div class="col3 push1 chrome-plugin">
	<h3>Chrome Extension</h3>
	<p>Passbolt requires a plugin that can be installed from the Chrome Web Store.</p>
	<p>
		<a href="#TODO" target="_blank">
		<img src="img/ChromeWebStore_Badge_v2_340x96.png" style="width: 225px; margin-top:10px;">
		</a>
	</p>
</div>
<div class="col3 push1 firefox-plugin">
	<h3>Firefox Plugin</h3>
	<p>A Firefox version of the Passbolt extension is currently in development. Stay tuned!</p>
	<p><img src="img/firefox_logo_disabled.png" style="width: 150px; margin-top:10px;"></p>
</div>
<div class="col4">
	<h3>Donate</h3>
	<p>Do you like Passbolt? Passbolt is <a href="#TODO">free and open source</a>. We need your help to continue the development.</p>
	<a href="#TODO" class="button primary" style="margin-top:20px;">Donate</a>
</div>
</div>
</div>
</div>
</div>
