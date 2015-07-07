<?php
/**
 * Config Debug Page
 *
 * @copyright    copyright 2012 passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.View.Pages.debug.config
 * @since        version 2.12.7
 */
	if(Configure::Read('debug')) :
?>
	<h1 style="text-align:center;">Debug is enabled.</h1>
	<script>
		// nudge the add-on to launch the debug screen
		$(function(){
			var event = document.createEvent('CustomEvent');
			event.initCustomEvent('passbolt.config.debug', true, true, {});
			document.documentElement.dispatchEvent(event);
		});
	</script>
<?php else : ?>
	<h1 style="text-align:center;">Debug is not enabled.</h1>
<?php endif; ?>
