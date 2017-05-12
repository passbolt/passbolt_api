<?php
/**
 * Login first step
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
?>
<div class="col6 push1 information">
	<h2><?php echo __('Download firefox!'); ?></h2>
	<div class="plugin-check-wrapper">
		<?php echo $this->element('public/browser/browser_unsupported'); ?>
	</div>
	<?php echo $this->element('public/browser/other_browsers_support_coming'); ?>
</div>
<div class="col4 push1 last">
	<?php echo $this->element('public/logo'); ?>
	<div class="users login form">
		<div class="feedback">
			<i class="fa fa-download huge" ></i>
		</div>
		<div class="actions-wrapper center">
			<a class="button primary big" href="https://www.mozilla.org/firefox">
				<?php echo __('Download Firefox'); ?>
			</a>
		</div>
	</div>
</div>
