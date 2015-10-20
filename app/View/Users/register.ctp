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
$this->assign('page_classes','register');
?>
<div class="grid">
	<div class="row">
		<div class="col6 push1 information">
			<h2><?php echo __('Try passbolt today!'); ?></h2>
			<p>
				<?php echo __('Enter your details in the form.'); ?>
				<?php echo __('We will send you an email to get you started.'); ?>
			</p>
			<div class="message-wrapper">
				<p class="message warning"><strong><?php echo __('Disclaimer:'); ?></strong>
					<?php echo __('please note this is a demo instance of Passbolt for trial purposes only.'); ?>
					<?php echo __('Do not use it to store sensitive data.'); ?><br>
					<a href="#"><?php echo __('find out more.'); ?></a>
				</p>
			</div>
			<p>
				<?php
				echo __('By signing up, you agree to the %sTerms of Service%s and %sPrivacy Policy%s, including %sCookie Use%s.',
						'<a href="#">','</a>',
						'<a href="#">','</a>',
						'<a href="#">','</a>'
				); ?>
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
				<div class="actions-wrapper">
					<div class="submit"><input type="submit" class="button primary big" value="<?php echo __('register'); ?>"></div>
					<a href="/login" class="secondary"><?php echo __('already a member?'); ?></a>
				</div>
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