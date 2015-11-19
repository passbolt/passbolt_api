/**
 * @page passbolt Passbolt
 * @tag passbolt
 * @parent index
 *
 * The passbolt page
 * 
 */
import 'app/bootstrap';
import 'lib/p3_narrow/p3.narrow';

$(document).ready(function () {
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
	// Start the application bootstrap.
	var bootstrap = new passbolt.Bootstrap();
});
