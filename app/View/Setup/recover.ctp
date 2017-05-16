<?php
/**
 * Install very first step.
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
$this->assign('title',	__('Recover an account'));
$this->assign('page_classes', 'setup recover');
$this->Html->css('setup.min', null, array('block' => 'css'));
$this->Html->script('pages/install.js', array('inline' => false, 'block'=>'scriptBottom'));

// Only Firefox and Chrome are supported right now.
$browser = strtolower($userAgent['Browser']['name']);
if ($browser == 'firefox' || $browser == 'chrome') {
	$pluginCheckTemplate = 'public/Setup/' . $browser;
} else {
	$pluginCheckTemplate = 'public/Setup/browser_unsupported';
}
?>

<input type="hidden" id="js_setup_user_username" value="<?php echo $user['User']['username'] ?>"/>
<input type="hidden" id="js_setup_user_first_name" value="<?php echo $user['Profile']['first_name'] ?>"/>
<input type="hidden" id="js_setup_user_last_name" value="<?php echo $user['Profile']['last_name'] ?>"/>

<!-- first header -->
<div class="header first">
	<nav>
		<div class="primary navigation top">
			<!-- no top links at setup -->
		</div>
	</nav>
</div>

<!-- second header -->
<div class="header second">
	<div class="col1">
		<div class="logo no-img">
			<h1><span>Passbolt</span></h1>
		</div>
	</div>
	<div class="col2_3">
		<h2 id="js_step_title"><?php echo __('Account recovery: let\'s take 5 min to reconfigure your plugin!') ?></h2>
	</div>
</div>

<div class="panel main ">
	<!-- wizard steps -->
	<div class="panel left">
		<div class="navigation wizard">
			<ul>
				<li class="selected">
					<?php echo __('1. Security checks') ?>
				</li>
				<li class="disabled">
					<?php echo __('2. Import key') ?>
				</li>
				<li class="disabled">
					<?php echo __('4. Set a new security token') ?>
				</li>
				<li class="disabled">
					<?php echo __('5. Login!') ?>
				</li>
			</ul>
		</div>
	</div>
	<!-- main -->
	<div class="panel middle">
		<div class="grid grid-responsive-12">
			<div class="row">
				<div class="col7">
					<?php echo $this->element($pluginCheckTemplate); ?>
				</div>
				<div class="col5 last"></div>
			</div>
		</div>
	</div>
</div>