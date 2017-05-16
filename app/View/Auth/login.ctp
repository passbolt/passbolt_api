<?php
/**
 * Login Form View (for guest role)
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
	$this->assign('title',	__('Login'));
	$pageClasses = 'login';
	if (Configure::read('App.registration.public') === true) {
		$pageClasses .= ' public-registration';
	}
	$this->assign('page_classes', $pageClasses);
	$this->Html->css('login.min', null, array('block' => 'css'));
	$this->Html->script('lib/jquery/dist/jquery.js', array('inline' => false, 'block'=>'scriptHeader'));

	// Only Firefox is supported right now.
	$browser = strtolower($userAgent['Browser']['name']);
	$pluginCheckTemplate = 'public/Auth/browser_unsupported';
	if ($browser == 'firefox' || $browser == 'chrome') {
		$pluginCheckTemplate = 'public/Auth/' . $browser;
	}
?>
<div class="grid">
	<div class="row js_main-login-section">
		<?php echo $this->element($pluginCheckTemplate); ?>
	</div>
	<div class="row">
		<div class="col3 push1 github-block">
			<?php echo $this->element('public/box-service-cloud'); ?>
		</div>
		<div class="col3 github-block">
            <?php echo $this->element('public/box-service-pro'); ?>
        </div>
		<div class="col4 github-block push1 last">
			<?php echo $this->element('public/box-open-source'); ?>
		</div>
	</div>
</div>
