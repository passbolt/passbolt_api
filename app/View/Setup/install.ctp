<?php
/**
 * Install very first step.
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
$this->assign('title',	__('Install'));
$this->assign('page_classes', 'setup install');
$this->Html->css('setup.min', null, array('block' => 'css'));
$this->Html->script('pages/install.js', array('inline' => false, 'block'=>'scriptBottom'));
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
		<h2 id="js_step_title"><?php echo __('Welcome to passbolt! Let\'s take 5 min to setup your system.') ?></h2>
	</div>
</div>

<div class="panel main ">
	<!-- wizard steps -->
	<div class="panel left">
		<div class="navigation wizard">
			<ul>
				<li class="selected">
					<?php echo __('1. Get the plugin') ?>
				</li>
				<li class="disabled">
					<?php echo __('2. Define your keys') ?>
				</li>
				<li class="disabled">
					<?php echo __('3. Set a master password') ?>
				</li>
				<li class="disabled">
					<?php echo __('4. Set a security token') ?>
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
					<div class="plugin-check-wrapper">
						<h3><?php echo __('Plugin check') ?></h3>
						<?php echo $this->element('public/plugin/firefox-no-addon'); ?>
					</div>
					<div class="why-plugin-wrapper">
						<h3><?php echo ("Why do I need a plugin"); ?></h3>
						<p>
							<?php echo __('Passbolt requires a browser add-on to guarantee that your secret key and your master password are never
							accessible to any website (including passbolt.com itself). This is also the only way to guarantee that
							the core cryptographic libraries cannot be tampered with.'); ?>
						</p>
					</div>
					<div class="submit-input-wrapper">
						<a id="js_setup_plugin_check" href="#" class="button primary big">retry</a>
					</div>
				</div>
				<div class="col5 last">
<!--					<div class="video-wrapper">-->
<!--						<iframe width="400" height="300" src="https://www.youtube.com/embed/u-vDLf7cmf0" frameborder="0" allowfullscreen></iframe>-->
<!--					</div>-->
				</div>
			</div>
		</div>
	</div>
</div>