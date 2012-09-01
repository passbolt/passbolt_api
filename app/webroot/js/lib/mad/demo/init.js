APP_URL = 'http://passbolt.local';
MAD_ROOT = 'lib/mad';

steal(
	MAD_ROOT + '/mad.js' // the mad framework
).then(function () {

	steal.options.logLevel = 0;

	//load the bootstrap of the application
	var boot = new mad.bootstrap.AppBootstrap({
		'appRootUrl': 'http://passbolt.local', // Application root url
		'lg': 'en-EN', // The langue of the application
		'appNamespaceId': 'demo', // Application namespace
		'appControllerId': 'js_demo_app_controller', // Application controller DOM node id
		'appControllerClass': mad.controller.AppController, // Application controller class
		'eventBusControllerId': 'mad_test_event_bus_controller' // Event bus controller DOM node id
	});

});