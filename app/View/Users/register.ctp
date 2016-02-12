<?php
/**
 * Register form view (for guest role)
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
$this->assign('title',	__('Register'));
$this->Html->css('login.min', null, array('block' => 'css'));
$this->assign('page_classes', 'register');
?>
<div class="grid">
	<div class="row">
		<div class="col6 push1 information">
			<h2><?php echo __('Try passbolt demo today!'); ?></h2>
			<div class="message-wrapper">
				<p class="message error"><strong><?php echo __('Important Disclaimer:'); ?></strong><br>
					<?php echo __('This is a demo instance of Passbolt for trial purposes only.'); ?>
					<?php echo __('Do not use it to store sensitive information.'); ?>
					<?php echo __('Do not use it if you are not confortable with other testers being able to see your name and email address.'); ?>
				</p>
			</div>
			<p>
				<?php
				echo __('By signing up, you agree to the %sTerms of Service%s and %sPrivacy Policy%s, including our use of Cookies.',
						'<a href="https://www.passbolt.com/terms">','</a>',
						'<a href="https://www.passbolt.com/privacy">','</a>'
				); ?>
			</p>
			<p>
				<?php echo __('Enter your details in the form.'); ?>
				<?php echo __('We will send you an email to get you started.'); ?>
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

</div>