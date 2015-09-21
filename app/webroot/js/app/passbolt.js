/**
 * @page passbolt Passbolt
 * @tag passbolt
 * @parent index
 *
 * The passbolt page
 * 
 */
import 'mad/mad';
import 'mad/bootstrap';
import passbolt from 'app/util/util'; // @todo rename to setting maybe.
import 'app/error/error_handler';
import 'app/net/response_handler';
import 'app/component/app';
import appConfig from 'app/config/config.json';

$(document).ready(function () {
	// Load the config packaged with the front-end application.
	mad.Config.load(appConfig);

	// Load the dynamic config served by the back-end.
	mad.Config.load(cakephpConfig);

	// Start the application bootstrap.
	var bootstrap = new mad.Bootstrap();
});
