APP_URL = 'http://passbolt.local';

steal(
	'mad/mad.js'
).then(
	// load the usefull models
	'mad/test/data/model/person.js',
	'mad/test/data/model/country.js'
).then(function () {

	$(document).ready(function () {

		//load the bootstrap of the application
		var boot = new mad.bootstrap.AppBootstrap({
			// Application root url
			'appRootUrl': APP_URL,
			// The langue of the application
			'lg': 'en-EN',
			// Application namespace
			'appNamespaceId': 'mad_test',
			// Application controller DOM node id
			'appControllerId': 'mad_test_app_controller',
			// Application controller class
			'appControllerClass': mad.controller.AppController,
			// Event bus controller DOM node id
			'eventBusControllerId': 'mad_test_event_bus_controller',
			'callbacks' : {
				'ready': function () {
					$('body').addClass('mad_test_app_ready');
				}
			}
		});

	});

});