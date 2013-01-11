<?php
/**
 * Login Form View (for guest role)
 *
 * @copyright    copyright 2012 passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.View.Users.login
 * @since        version 2.12.9
 */
	$this->assign('title',  __('Login'));
	$this->Html->css('960/960_16_col.css', null, array('block' => 'css'));
	$this->Html->css('login.css', null, array('block' => 'css'));
	$this->Html->script('lib/jquery/jquery-1.8.2.min.js', array('inline' => false, 'block'=>'scriptHeader'));
	$this->Html->script('pages/login.js', array('inline' => false, 'block'=>'scriptHeader'));
?>
  <div class="grid_9 information">
		<h2>Welcome back!</h2>
		<p>
			You will need to login to access your passwords.
      Your organization have decided of the following access rules:
    </p>
    <ul>
      <li>Accounts are created by administrators.</li>
      <li>Your password does not expire automatically.</li>
      <li>No certificate required.</li>
      <li>No second factor authentication.</li>
		</ul>
		<p>
      Do you have problems loging in? <a href="#">Contact an administrator</a>.
    </p>
  </div>
  <div class="grid_6 push_1">
    <div class="users login form">
    <?php echo $this->MyForm->create('User');?>
      <fieldset>
        <legend><?php echo __('Please enter your username and password'); ?></legend>
        <?php echo $this->MyForm->input('User.username', array('label' => __('Username'), 'class' =>'required')); ?>
        <?php echo $this->MyForm->input('User.password', array('label' => __('Password'), 'class' =>'required')); ?>
      </fieldset>
      <?php echo $this->MyForm->submit(__('login'));?>
			<span class="forgot"><a href="#">forgot password?</a></span>
			<?php echo $this->PassboltAuth->get(); ?>
    <?php echo $this->MyForm->end();?>
    </div>
  </div>
