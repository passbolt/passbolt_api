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
// The mad librarie path with the JMVC directory as root directory
MAD_ROOT = 'lib/mad';

steal(
	MAD_ROOT + '/mad.js' // the mad framework
).then(
	'jquery/plugin/jquery-ui-1.8.20.custom.min.js', // load jquery ui lib    
	'app/bootstrap/appBootstrap.js', // passbolt application bootstrap
	'app/controller/appController.js' // passbolt main application controller
//	'app/test/data/resources.js' // resources data test
).then(function () {

	steal.options.logLevel = 1;

	$(document).ready(function () {

		//load the bootstrap of the application
		var boot = new passbolt.bootstrap.AppBootstrap({
			'appRootUrl': 'http://passbolt.local', // Application root url
			'dictionary': 'en-EN', // The langue of the application
			'appNamespaceId': 'passbolt', // Application namespace
			'appControllerId': 'js_app_controller', // Application controller DOM node id
			'appControllerClass': passbolt.controller.AppController, // Application controller class
			'errorHandlerClass': passbolt.helper.ErrorHandler, // Set the Error handler class
			'responseHandlerClass': passbolt.helper.ResponseHandler, // Set the Response handler class
			'eventBusControllerId': 'passbolt_event_bus_controller', // Event bus controller DOM node id
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
