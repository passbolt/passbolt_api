steal(
    'jquery/dom/route',
    'mad/bootstrap/bootstrapInterface.js',
	'mad/event/eventBus.js',
	'mad/devel/devel.js',
    'mad/helper/htmlHelper.js',
    'mad/config/config.js'
).then(function () {

	/*
	 * @class mad.bootstrap.AppBootstrap
	 * @inherits mad.bootstrap.BootstrapInterface
	 * @parent mad.core
	 * 
	 * The AppBoostrap class is the application launcher. It takes care of :
	 * 
	 * * Initialise the application namespace
	 * * Set the global variables
	 * * Initialise the event bus
	 * * Initialise the route listener
	 * * Initialise the translation engine
	 * * Initialise the application
	 * * Dispatch to the convenient action following the route
	 * 
	 *	##Example
	 *	The bootstrap use by the demo found in the documentation
	 *	
	 *	@codestart
	// Launch the application and its master pieces
	var boot = new passbolt.bootstrap.AppBootstrap({ 'config': [ 'app/config/config.json' ] });
	 *	@codeend
	 * 
	 * 
	 * ##Config Example
	 * 
	 *	@codestart
	{
		"app": {
			"url": "http://passbolt.local",
			"controllerElt": "#js_app_controller",	
			"namespace": "passbolt",
			"ControllerClassName": "passbolt.controller.AppController"
		},
		"error": {
			"ErrorHandlerClassName": "passbolt.helper.ErrorHandler"
		},
		"event": {
			"eventBusControllerElt": "#js_bus_controller"
		},
		"i18n": {
			"lang": "EN-en"
		},
		"net": {
			"ResponseHandlerClassName": "passbolt.net.ResponseHandler"
		},
		"route": {
			"defaultRoute": {
				"extension": "passbolt",
				"controller": "passwordWorkspace",
				"action": "index"
			}
		}
	}
	 *	@codeend
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
	 */	
	mad.bootstrap.BootstrapInterface.extend('mad.bootstrap.AppBootstrap', /* @static */ {

		'defaults': {
			// config files uri
			'config': [ 'mad/config/config.json' ],
			// callbacks
			'callbacks': {
				'ready': null
			}
		}

	}, /*  @prototype */ {

		// constructor like
		'init': function (options) {
			this.options = {};
			
			// Define the config variable by merging the config given in options widht the default configuration of mad
			var configFiles = [];
			$.merge($.merge(configFiles, mad.bootstrap.AppBootstrap.defaults.config), options.config);
			// extend default options with args options (merge manually array, extends override)
			$.extend(true, this.options, mad.bootstrap.AppBootstrap.defaults, options);

			// load config files
			for (var i in configFiles) {
				mad.Config.loadFile(configFiles[i]);
			}

			// load cakephp config
			if (typeof cakephpConfig != 'undefined') {
				mad.Config.load(cakephpConfig);
			}

			// APP_URL super variable
			var appUrl = mad.Config.read('app.url');
			if (typeof appUrl == 'undefined') {
				throw new mad.error.WrongConfigException('app.url');
			}
			APP_URL = appUrl;

			// Check the configuration

			// Define Error Handler Class
			var ErrorHandlerClass = can.getObject(mad.Config.read('error.ErrorHandlerClassName'));
			// Has to be a mad.error.ErrorHandler
			if (!ErrorHandlerClass) {
				throw new mad.error.WrongConfigException('error.ErrorHandlerClassName');
			}
			mad.Config.write('error.ErrorHandlerClass', ErrorHandlerClass);

			// Define Response Handler Class
			var ResponseHandlerClass = can.getObject(mad.Config.read('net.ResponseHandlerClassName'));
			// Has to be a mad.net.ResponseHandler
			if (!ResponseHandlerClass) {
				throw new mad.error.WrongConfigException('net.ResponseHandlerClassName');
			}
			mad.Config.write('net.ResponseHandlerClass', ResponseHandlerClass);

			// Define App Controller Class
			var AppControllerClass = can.getObject(mad.Config.read('app.ControllerClassName'));
			// Has to be a mad.net.ResponseHandler
			if (!AppControllerClass) {
				throw new mad.error.WrongConfigException('app.ControllerClassName');
			}
			mad.Config.write('app.AppControllerClass', AppControllerClass);

			// The app url has to be defined
			if ($.trim(mad.Config.read('app.url')) === '') {
				throw new mad.error.WrongConfigException('app.url');
			}

			// The app controller element has to be defined, and to be a reference to
			// an existing DOM element
			if (!$(mad.Config.read('app.controllerElt')).length) {
				throw new mad.error.WrongConfigException('app.controllerElt');
			}

			// Reference the application namespace if it does not exist yet
			var ns = can.getObject(mad.Config.read('app.namespace'), window, true);

			// Load the required component
			var components = mad.Config.read('core.components');
			for (var i in components) {
				if (components[i] == 'Devel' && (mad.Config.read('app.debug') == null ||
					mad.Config.read('app.debug') == 0)) {
					continue;
				}
				this['init' + components[i]]();
			}
		},

		/** 
		 * Initialize the internationalization service
		 * @return {void}
		 */
		'initInternationalization': function () {
			// Load the javascript dictionnary
			mad.net.Ajax.request({
				'type': 'GET',
				'url': APP_URL + 'dictionaries/en-EN.json',
				'async': false,
				'dataType': 'json',
				'success': function (request, response, data) {
					// load the client dictionnary
					mad.lang.I18n.loadDico(data);
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
		'initAppController': function () {
			var self = this;
			mad.bus.bind('app_ready', function () {
				if (self.options.callbacks.ready) {
					self.options.callbacks.ready();
				}
			});
			var AppControllerClass = can.getObject(mad.Config.read('app.ControllerClassName'));
			var app = new AppControllerClass($(mad.Config.read('app.controllerElt')));
			app.start();
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
		 * Initialize the Application Event Bus Controller
		 * @return {void}
		 */
		'initEventBus': function () {
			var elt = mad.helper.HtmlHelper.create(
				$(mad.Config.read('app.controllerElt')),
				'before',
				'<div/>'
			);
			var eventBus = new mad.event.EventBus(elt);
			mad.bus = eventBus;
		},

		/**
		 * Initialize the Application Development tools
		 * @return {void}
		 */
		'initDevel': function () {
			var elt = mad.helper.HtmlHelper.create(
				$(mad.Config.read('app.controllerElt')),
				'before',
				'<div/>'
			);
			var dev = new mad.devel.Devel(elt);
			mad.dev = dev;
		},

		/**
		 * Initialize the route listener of the application. It will be in charge to listen any changes
		 * on the hash and use the function dispatch to perform the desired action.
		 * @return {void}
		 */
		'initRouteListener': function (routes) {
			var self = this;
			mad.bus.bind(mad.APP_NS_ID + '_route_change', function (event, route) {
				self.dispatch(route);
			});
			new mad.route.RouteListener();
		}

//		/**
//		 * Dispatch to the right action following the hash url
//		 * @param {mad.route.Route} route The route to dispatch to
//		 * @see core.controller::getDispatcher()
//		 * @return {void}
//		 */
//		'dispatch': function (route) {
//			// check all required parameters are here
//			if (typeof route.extension == 'undefined') {
//				throw new Error('Bootstrap error : the url is not valid, extension missing');
//			} else if (typeof route.controller == 'undefined') {
//				throw new Error('Bootstrap error : the url is not valid, controller missing');
//			} else if (typeof route.action == 'undefined') {
//				throw new Error('Bootstrap error : the url is not valid, action missing');
//			}
//
//			// get the target controller
//			var controllerName = route.controller.charAt(0).toUpperCase() + route.controller.slice(1) + 'Controller';
//			var appNs = mad.getGlobal('APP_NS');
//			var controllerClass = null;
//			if (route.extension == 'passbolt') {
//				controllerClass = appNs.controller[controllerName];
//			} else {
//				controllerClass = appNs[route.extension].controller[controllerName];
//			}
//
//
//			// dispatch to the convenient action
//			steal.dev.log('dispatch to extension:' + route.extension + ' controller:' + controllerName + ' action:' + route.action);
//			this.options.dispatchOptions.ControllerClass = controllerClass;
//			controllerClass.getDispatcher().dispatch(route, this.options.dispatchOptions);
//		}
	});
});
