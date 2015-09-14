/*
 * @page passbolt Passbolt
 * @tag passbolt
 * @parent index
 *
 * The passbolt page
 * 
 */
import 'mad';
import 'app/bootstrap/bootstrap';
import 'app/component/app';
import 'app/net/response_handler';

$(document).ready(function () {

	//load the bootstrap of the application
	var boot = new passbolt.Bootstrap({
		'config': ['app/config/config.json']
	});

});

