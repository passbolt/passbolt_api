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
			'config': [
				'mad/config/config.json'
			],
			'callbacks' : {
				'ready': function () {
					$('body').addClass('mad_test_app_ready');
				}
			}
		});

	});

});
// 
// // The application url
// APP_URL = document.location.protocol + '//' + document.location.hostname;
// 
// steal(
	// // the mad framework
	// 'mad'
// ).then(function () {
// 
// //	steal.options.logLevel = 0;
// 
	// //load the bootstrap of the application
	// var boot = new mad.bootstrap.AppBootstrap({
		// 'appRootUrl': 'http://passbolt.local', // Application root url
		// 'lg': 'en-EN', // The langue of the application
		// 'appNamespaceId': 'demo', // Application namespace
		// 'appControllerId': 'js_demo_app_controller', // Application controller DOM node id
		// 'appControllerClass': mad.controller.AppController, // Application controller class
		// 'eventBusControllerId': 'mad_test_event_bus_controller' // Event bus controller DOM node id
	// });
// 
// });