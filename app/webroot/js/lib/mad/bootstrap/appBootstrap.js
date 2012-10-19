steal(
    'jquery/dom/route',
    MAD_ROOT + '/bootstrap/bootstrapInterface.js'
//    'plugin/activity/bootstrap/bootstrap.js'                  // Extension bootstrap, should be enabled by the php script
).then(function ($) {

	/*
	 * @class mad.bootstrap.AppBootstrap
	 * @inherits mad.bootstrap.BootstrapInterface
	 * @parent mad.core
	 * 
	 * The framework AppBoostrap class is our application launcher. It performs several task to setup the 
	 * application environment :
	 * <ul>
	 *	<li>Initialize the application namespace</li>
	 *	<li>Set the global variables</li>
	 *	<li>Initialize the event bus</li>
	 *	<li>Initialize the route listener</li>
	 *	<li>Initialize the translation engine</li>
	 *	<li>Initialize the application</li>
	 *	<li>Dispatch to the conveniant action following the route</li>
	 * </ul>
	 * 
	 * <p>
	 *	<h2>Example</h2>
	 *	The bootstrap use by the demo found in the documentation
	 *	
	 *	@codestart
	var boot = new mad.bootstrap.AppBootstrap({
		'appRootUrl': 'http://passbolt.local', // Application root url
		'lg': 'en-EN', // The langue of the application
		'appNamespaceId': 'demo', // Application namespace
		'appControllerId': 'js_demo_app_controller', // Application controller DOM node id
		'appControllerClass': mad.controller.AppController, // Application controller class
		'eventBusControllerId': 'mad_test_event_bus_controller' // Event bus controller DOM node id
	});
	 *	@codeend
	 * </p>
	 * 
	 * @constructor
	 * Creates a Application Bootstrap
	 * @param {Array} options Array of options
	 * @param {String} options.appControllerId Id of the application controller. A DOM element with this ID must
	 * exist on your page. Default : app-controller
	 * @param {Array} options.dispatchOptions Array of options for the dispatcher. See the Class mad.bootstrap.DispatcherInterface
	 * @param {Array} defaultRoute The default route used by the dispatcher
	 * @param {String} defaultRoute.extension The default extension
	 * @param {String} defaultRoute.controller The default controller
	 * @param {String} defaultRoute.action The default action
	 * @return {mad.bootstrap.AppBootstrap}
	 * 
	 * @todo write states schema of the application
	 */
	mad.bootstrap.BootstrapInterface.extend('mad.bootstrap.AppBootstrap', /* @static */ {

		'defaults': {
			'appRootUrl': '',
			'lang': 'en-EN',
			'appControllerId': 'app-controller',
			'appNamespaceId': 'app',
			'appControllerClass': mad.controller.AppController,
			'errorHandlerClass': mad.error.ErrorHandler,
			'responseHandlerClass': mad.net.ResponseHandler,
			'dispatchOptions': {},
			'defaultRoute': null,
			'callbacks': {
				'ready': null
			}
		}

	}, /*  @prototype */ {

		'init': function (options) {
			// the current route
			this.currentRoute = null;
			// the event bus controller
			this.bus = null;
			// array of options passed by args
			this.options = {};
			// extend default options with args options
			this.options = $.extend(true, {}, mad.bootstrap.AppBootstrap.defaults, options);

			// @todo check resource of this kind of big try catch
			try {

				// check compulsory options (an option compulsory lol)
				if ($.trim(this.options.appRootUrl) === '') {
					throw new mad.error.MissingOptionException('appRootUrl', 'mad.bootstrap.AppBootstrap');
				}

				// Reference the application namespace
				mad.controller.AppController.setNs(this.options.appNamespaceId);

				// find the controller with the given appControllerId passed by args
				var $appController = mad.setGlobal('$appController', $('#' + this.options.appControllerId));
				// if the DOM does not contain a reference to an element with the given appControllerId
				// throw an Error
				if (!$appController.length) {
					throw new Error('AppBootstrap error : Your template must contain a node element with the id (' + this.options.appControllerId + ')');
				}

				// 
				// BEGINING OF THE APPLICATION BOOTSTRAP PROCESS
				// 

				// Initialize app globals variables
				this.initConstants();

				// Initialize app globals objects
				this.initGlobals();

				// Initialize internationalization
				this.initInternationalization();

				// Initialize the event bus controller
				this.initEventBus();

				// Initialize the route listener of the application. It will be in charge to listen any changes
				// on the hash and use the function dispatch to perform the desired action.
				this.initRouteListener();

				// Initialize the application
				this.initApplication();

				// Initialize extensions
				//	                this.initExtensions();

				// Dispatch to the right action
				var route = mad.route.RouteListener.singleton().getRoute();
				if (route == null && this.options.defaultRoute != null) {
					route = this.options.defaultRoute;
				}
				if (route != null) {
					this.dispatch(route);
				}

				// Application is ready
				this.ready();

			} catch (ex) {
				// Catch any exceptions released by the system
				this.options.errorHandlerClass.handleException(ex);
			}

			//
			// END OF THE APPLICATION BOOTSTRAP PROCESS
			// 
		},

		/**
		 * Init application constants
		 * @return {void}
		 */
		'initConstants': function () {
			// init globals
			mad.setGlobal('APP_ROOT_URL', this.options.appRootUrl);
			mad.setGlobal('LG', this.options.lg);
			mad.setGlobal('APP_NS_ID', this.options.appNamespaceId);
			mad.setGlobal('APP_NS', window[mad.controller.AppController.getGlobal('APP_NS_ID')]);
			mad.setGlobal('APP_CONTROLLER_ID', this.options.appControllerId);
			mad.setGlobal('EVENTBUS_CONTROLLER_ID', this.options.eventBusControllerId);
			mad.setGlobal('APP_CONTROLLER_CLASS', this.options.appControllerClass);
			mad.setGlobal('ERROR_HANDLER_CLASS', this.options.errorHandlerClass);
			mad.setGlobal('RESPONSE_HANDLER_CLASS', this.options.responseHandlerClass);
		},

		/**
		 * Init application globals
		 * @return {void}
		 */
		'initGlobals': function () {
			mad.setGlobal('eventBus', null);
			mad.setGlobal('app', null);
		},

		/** 
		 * Initialize the internationalization service
		 * @return {void}
		 */
		'initInternationalization': function () {
			// Load the javascript dictionnary
			mad.net.Ajax.singleton().request({
				'type': mad.net.Request.METHOD_GET,
				'url': mad.getGlobal('APP_ROOT_URL') + '/dictionaries/en-EN.json',
				'async': false,
				'dataType': 'json',
				'success': function (request, response, data) {
					// load the client dictionnary
					mad.lang.I18n.singleton().loadDico(data);
				},
				'error': function (request, response) {
					steal.dev.warn('Unable to load the client dictionnary');
				}
			});
		},

		/**
		 * Init application
		 * @return {void}
		 */
		'initApplication': function () {
			var appControllerClass = this.options.appControllerClass;
			var app = appControllerClass.singleton(mad.getGlobal('$appController'));
			mad.setGlobal('app', app);
			mad.app = mad.getGlobal('app');
		},

		/**
		 * Init application's extensions
		 * @return {void}
		 * @todo make the subscription to the application for the extensions more clear
		 */
		'initExtensions': function () {
			var activityBootstrap = new passbolt.activity.bootstrap.Bootstrap();
		},

		/**
		 * Initialize the event bus controller of the application. It will be in charge to centralize
		 * all events which occur
		 * @return {void}
		 * @todo change the use to mad.eventBus in mad.$eventBus (because it is a jQuery element)
		 */
		'initEventBus': function () {
			// initialize the event bus of the application
			var pluginNameController = mad.event.EventBus._fullName;
			// add the dom element which will be behind the controller
			mad.getGlobal('$appController').before('<div id="' + mad.getGlobal('EVENTBUS_CONTROLLER_ID') + '"></div>');
			// instantiate the event bus controller
			var eventBus = new mad.event.EventBus('#' + mad.getGlobal('EVENTBUS_CONTROLLER_ID'));
			mad.setGlobal('eventBus', eventBus.element);
			// Make an alias with the eventBus
			mad.eventBus = mad.getGlobal('eventBus');
		},

		/**
		 * Initialize the route listener of the application. It will be in charge to listen any changes
		 * on the hash and use the function dispatch to perform the desired action.
		 * @return {void}
		 */
		'initRouteListener': function (routes) {
			var self = this;
			mad.eventBus.bind(mad.APP_NS_ID + '_route_change', function (event, route) {
				self.dispatch(route);
			});
			mad.route.RouteListener.singleton();
		},

		/**
		 * Dispatch to the right action following the hash url
		 * @param {mad.route.Route} route The route to dispatch to
		 * @see core.controller::getDispatcher()
		 * @return {void}
		 */
		'dispatch': function (route) {
			// check all required parameters are here
			if (typeof route.extension == 'undefined') {
				throw new Error('Bootstrap error : the url is not valid, extension missing');
			} else if (typeof route.controller == 'undefined') {
				throw new Error('Bootstrap error : the url is not valid, controller missing');
			} else if (typeof route.action == 'undefined') {
				throw new Error('Bootstrap error : the url is not valid, action missing');
			}

			// get the target controller
			var controllerName = route.controller.charAt(0).toUpperCase() + route.controller.slice(1) + 'Controller';
			var appNs = mad.getGlobal('APP_NS');
			var controllerClass = null;
			if (route.extension == 'passbolt') {
				controllerClass = appNs.controller[controllerName];
			} else {
				controllerClass = appNs[route.extension].controller[controllerName];
			}


			// dispatch to the convenient action
			steal.dev.log('dispatch to extension:' + route.extension + ' controller:' + controllerName + ' action:' + route.action);
			this.options.dispatchOptions.ControllerClass = controllerClass;
			controllerClass.getDispatcher().dispatch(route, this.options.dispatchOptions);
		},

		/**
		 * Execute this function at the end of the bootstrap process.
		 * @return {void}
		 */
		'ready': function () {
			// @todo used by the unit test
			// The app controller should release this event ?
			// The app is ready before or after dispatch ?
			if (this.options.callbacks.ready != null) {
				this.options.callbacks.ready();
			}
			mad.app.ready();
		}
	});
});