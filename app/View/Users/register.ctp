<?php
/**
 * Register form view (for guest role)
 *
 * @copyright	copyright 2012 passbolt.com
 * @license		http://www.passbolt.com/license
 * @package		app.View.Users.register
 * @since		version 2.12.9
 */
$this->assign('title',	__('Register'));
$this->Html->css('login', null, array('block' => 'css'));
$this->Html->script('lib/jquery/jquery-1.8.3.js', array('inline' => false, 'block'=>'scriptHeader'));
?>
<div class="grid">
	<div class="row">
		<div class="col6 push1 information">
			<h2>Try passbolt today!</h2>
			<p>
				Enter your details in the form.
				We will send you an email to get you started.
			</p>
			<div class="message-wrapper">
				<p class="message warning"><strong>Disclaimer:</strong> please note this is a demo instance of Passbolt for trial purposes only.
					Do not use it to store sensitive data.<br>
					<a href="#">find out more</a>.
				</p>
			</div>
			<p>By signing up, you agree to the
				<a href="#">Terms of Service</a> and
				<a href="#">Privacy Policy</a>, including
				<a href="#">Cookie Use</a>.
			</p>
		</div>
		<div class="col4 push1 last">
			<div class="logo">
				<h1><a href="#"><span>Passbolt</span></a></h1>
			</div>
			<div class="users register form">
				<?php echo $this->MyForm->create('User');?>
				<fieldset>
					<legend><?php echo __('Please enter your username and password'); ?></legend>
					<?php echo $this->MyForm->input('Profile.first_name', array('label' => __('First name'), 'class' =>'required fluid')) ?>
					<?php echo $this->MyForm->input('Profile.last_name', array('label' => __('Last name'), 'class' =>'required fluid')) ?>
					<?php echo $this->MyForm->input('User.username', array('label' => __('Email'), 'class' =>'required fluid')) ?>
				</fieldset>
				<?php echo $this->MyForm->submit(__('Register'));?>
				<span class="forgot"><a href="/login">already a member?</a></span>
				<?php echo $this->MyForm->end();?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col3 push1 github-block">
			<h3>We ♥ open source</h3>
			<p>Passbolt is free and open source. It uses
				<a href="openpgpjs.org/" target="_blank">OpenGPG.js</a>,
				<a href="http://www.javascriptmvc.com/" target="_blank">JavascriptMVC</a> and
				<a href="http://www.cakephp.org" target="_blank">Cakephp</a>.
			<p>
			<p>
				<a href="http://github.com/passbolt" class="button dim github">
					<i class="icon social github inline"></i>
					<span>github</span>
				</a>
			</p>
		</div>
		<div class="col3 chrome-plugin-block">
			<h3>Chrome Extension</h3>
			<p>A Chrome Web store Extension for Passbolt is currently under development. Stay tuned!</p>
			<p>
				<img src="/img/third_party/ChromeWebStore_disabled.png" alt="chrome web store"/>
			</p>
		</div>
		<div class="col4 donate-block push1 last">
			<h3>Donate</h3>
			<p>Do you like Passbolt? Passbolt is <a href="#">free and open source</a>. We need your help to continue the development.</p>
			<a href="#" class="button primary donate">Donate</a>
		</div>
	</div>
