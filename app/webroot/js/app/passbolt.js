/**
 * @page passbolt Passbolt
 * @tag passbolt
 * @parent index
 *
 * The passbolt page
 * 
 */
import 'mad/mad';
import 'app/bootstrap';
import 'lib/p3_narrow/p3.narrow';

$(document).ready(function () {
	// The deferreds returned by the ajax calls which retrieve the application configurations.
	var appConfigDeferred = null,
		notifConfigDeferred = null;

	// Adds classes to an element (body by default) based on document width.
	$.p3.narrow({
		sizes: {
			fourfour:   440,
			fourheight: 480,
			fivefour:   540,
			six: 		600,
			ninefive: 	980,
			nineheight: 980
		}
	});

	// Load the config served by the CakePHP.
	// The variable cakephpConfig is define directly in the DOM.
	mad.Config.load(cakephpConfig);

	// Retrieve the application core configuration.
	appConfigDeferred = $.getJSON(mad.Config.read('app.url') + 'js/app/config/config.json');
	// Retrieve the application notification configuration.
	notifConfigDeferred = $.getJSON(mad.Config.read('app.url') + 'js/app/config/notification.json');

	// When the application configurations have been retrieved.
	$.when(appConfigDeferred, notifConfigDeferred).done(function(appConfigDeferredResult, notifConfigDeferredResult) {
		// Load the application config.
		mad.Config.load(appConfigDeferredResult[0]);
		// Load notifications config.
		mad.Config.load(notifConfigDeferredResult[0]);

		// Start the application bootstrap.
		new passbolt.Bootstrap();
	});

});
