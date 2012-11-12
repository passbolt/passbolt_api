APP_URL = 'http://passbolt.local';

steal(
	'mad/mad.js'
).then(
	// laod the usefull models
	'mad/test/data/model/person.js',
	'mad/test/data/model/country.js'
).then(function () {

//	steal.options.logLevel = 0;

	$(document).ready(function () {

		//load the bootstrap of the application
		var boot = new mad.bootstrap.AppBootstrap({
			'appRootUrl': 'http://passbolt.local', // Application root url
			'lg': 'en-EN', // The langue of the application
			'appNamespaceId': 'mad_test', // Application namespace
			'appControllerId': 'mad_test_app_controller', // Application controller DOM node id
			'appControllerClass': mad.controller.AppController, // Application controller class
			'eventBusControllerId': 'mad_test_event_bus_controller', // Event bus controller DOM node id
			'callbacks' : {
				'ready': function () {
					console.log('ready');
					$('body').addClass('mad_test_app_ready');
				}
			}
		});

	});

});