<?php
/**
 * Config Debug Page
 *
 * @copyright    copyright 2012 passbolt.com
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.View.Pages.debug.config
 * @since        version 2.12.7
 */
	if(Configure::Read('debug')) :
?>
	<h1 style="text-align:center;">Debug is enabled.</h1>
<?php else : ?>
	<h1 style="text-align:center;">Debug is not enabled.</h1>
<?php endif; ?>
