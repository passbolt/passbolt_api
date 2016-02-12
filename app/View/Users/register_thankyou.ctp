<?php
/**
 * Register form view (for guest role)
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
$this->assign('title',	__('Register'));
$this->Html->css('login.min', null, array('block' => 'css'));
$this->assign('page_classes', 'register thank-you');
?>
<div class="grid">
	<div class="row">
		<div class="col6 push1 information">
			<h2><?php echo __('Thank you'); ?></h2>
			<p>
				<?php echo __('Thank you for giving passbolt a test run! We just sent you an email, please follow the instructions to get started.'); ?>
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
			<div class="register thank-you form feedback">
				<div class="graphical-feedback"><i class="fa fa-envelope-o huge"></i></div>
				<p>
					<strong><?php echo __('Email sent!'); ?></strong>
					<?php echo __('Please check your spam folder if you do not hear from us after a while.'); ?>
				</p>
			</div>
		</div>
	</div>
</div>
