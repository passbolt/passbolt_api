<?php
/**
 * Register form view (for guest role)
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
$this->assign('title',	__('Register'));
$this->Html->css('login.min', null, array('block' => 'css'));
$this->assign('page_classes', 'register');
$inputDefault = ['inputDefaults' => ['error' => [ 'attributes' => ['class' => 'message error']]]];
?>
<div class="grid">
	<div class="row">
		<div class="col6 push1 information">
			<h2><?php echo __('Try passbolt demo today!'); ?></h2>
			<p>
				<?php echo __('Enter your details in the form.'); ?>
				<?php echo __('We will send you an email to get you started.'); ?>
			</p>
<?php echo $this->element('public/disclaimer-legal'); ?>
		</div>
		<div class="col4 push1 last">
			<div class="logo">
				<h1><span>Passbolt</span></h1>
			</div>
			<div class="users register form">
				<?php echo $this->Form->create('User', $inputDefault); ?>
				<fieldset>
					<legend><?php echo __('Please enter your username and password'); ?></legend>
					<?php echo $this->Form->input('Profile.first_name', array('label' => __('First name'), 'class' =>' fluid')); ?>
					<?php echo $this->Form->input('Profile.last_name', array('label' => __('Last name'), 'class' =>' fluid')); ?>
					<?php echo $this->Form->input('User.username', array('label' => __('Email'), 'class' =>' fluid')); ?>
				</fieldset>
				<div class="actions-wrapper">
					<div class="submit"><input type="submit" class="button primary big" value="<?php echo __('register'); ?>"></div>
					<a href="<?php echo Router::url('/login'); ?>" class="secondary"><?php echo __('already a member?'); ?></a>
				</div>
				<?php echo $this->Form->end();?>
			</div>
		</div>
	</div>
</div>