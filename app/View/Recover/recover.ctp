<?php
/**
 * Register form view (for guest role)
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
$this->assign('title',	__('Recover an account'));
$this->Html->css('login.min', null, array('block' => 'css'));
$this->assign('page_classes', 'recover');
$inputDefault = ['inputDefaults' => ['error' => [ 'attributes' => ['class' => 'message error']]]];
?>
<div class="grid">
	<div class="row">
		<div class="col6 push1 information">
			<h2><?php echo __('Recover an existing account!'); ?></h2>
			<?php echo $this->element('public/recover/recover-explanations'); ?>
		</div>
		<div class="col4 push1 last">
			<div class="logo">
				<h1><span>Passbolt</span></h1>
			</div>
			<div class="users register form">
				<?php echo $this->Form->create('User', $inputDefault);?>
				<fieldset>
					<legend><?php echo __('Please enter your username and password'); ?></legend>
					<?php echo $this->Form->input('User.username', array('label' => __('Enter your email'), 'class' =>'required fluid')); ?>
				</fieldset>
				<div class="actions-wrapper">
					<div class="submit"><input type="submit" class="button primary" value="<?php echo __('start recovery'); ?>"></div>
				</div>
				<?php echo $this->Form->end();?>
			</div>
		</div>
	</div>

</div>