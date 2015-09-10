/*
 * @page passbolt Passbolt
 * @tag passbolt
 * @parent index
 *
 * The passbolt page
 * 
 */

steal(
	// the mad framework
	'mad/mad.js'
).then(
	// passbolt application bootstrap
	'app/bootstrap/appBootstrap.js',
	// passbolt main application controller
	'app/controller/appController.js',
	// the passbolt response handler
	'app/net/responseHandler.js'
).then(function () {

	$(document).ready(function () {

		//load the bootstrap of the application
		var boot = new passbolt.bootstrap.AppBootstrap({
			'config': ['app/config/config.json']
		});

	});

});
