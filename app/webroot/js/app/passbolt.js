/*
 * @page passbolt Passbolt
 * @tag passbolt
 * @parent index
 *
 * The passbolt page
 * 
 */

// The application url
APP_URL = document.location.protocol + '//' + document.location.hostname + document.location.pathname;

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
			// Application root url
			'appRootUrl': 'http://passbolt.local',
			// The langue of the application
			'dictionary': 'en-EN',
			// Application namespace
			'appNamespaceId': 'passbolt',
			// Application controller DOM node id
			'appControllerId': 'js_app_controller',
			// Application controller class
			'appControllerClass': passbolt.controller.AppController,
			// Set the Error handler class
			'errorHandlerClass': passbolt.helper.ErrorHandler,
			// Set the default Response handler class
			'responseHandlerClass': passbolt.net.ResponseHandler,
			// Event bus controller DOM node id
			'eventBusControllerId': 'passbolt_event_bus_controller',
			//                , 'dispatchOptions' : {                                                     // Dispatcher options (not used here, but in case of page to page application, the DOM node id of the page controller)
			//                    'pageControllerId'  : 'passbolt-page-controller'
			//                }
			'defaultRoute': { // The default route
				'extension': 'passbolt',
				'controller': 'passwordWorkspace',
				'action': 'index'
			}
		});

	});

});
