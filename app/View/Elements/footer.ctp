<?php
/**
 * Footer
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
?>
<!-- footer -->
<footer>
	<div class="footer">
		<ul class="footer-links">
			<li><a href="https://www.passbolt.com/terms" title="terms of service"><?php echo __('Terms'); ?></a></li>
			<li><a href="https://www.passbolt.com/privacy"><?php echo __('Privacy'); ?></a></li>
			<li><a href="https://www.passbolt.com/credits"><?php echo __('Credits'); ?></a></li>
			<li id="version">
				<a href="https://www.passbolt.com/credits" class="tooltip-left"
					data-tooltip="<?php echo Configure::read('App.version.number'); ?>">
					<i class="fa fa-heart-o"></i>
					<span class="visuallyhidden">About</span>
				</a>
			</li>
		</ul>
	</div>
</footer>
