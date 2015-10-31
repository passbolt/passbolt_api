steal(
	'mad/route/dispatcherInterface.js',
	'mad/helper/routeHelper.js'
).then(function () {

	/*
	 * @class mad.route.ExtensionControllerActionDispatcher
	 * @inherits mad.route.DispatcherInterface
	 * @parent mad.route
	 * 
	 * The ExtensionsControllerAction dispatcher is the common way to dispatch a route. Based
	 * on the extension name, the controller name and the action name it will dispatch the route
	 * to the target action of the target controller of the target extension.
	 * 
	 * <br/> For the url <b>http://mydomain/myExtension/myController/myAction</b>, the bootstrap extracts a route :
	 * 
	 * <u>extension</u> : myExtension
	 * <br/> <u>controller</u> : myController
	 * <br/> <u>action</u> : myAction
	 * 
	 * First of all the route is used to find the controller in the DOM page. If the <b>options.controllerId</b>
	 * is empty the dispatcher uses the standard <b>myExtension-myController-controller</b> to identify the controller
	 * DOM element.
	 * 
	 * Once the controller is identified, the dispatcher calls the action on this controller. The
	 * action is an instance's function of the controller.
	 */
	mad.route.DispatcherInterface.extend('mad.route.ExtensionControllerActionDispatcher', /** @static */ {

		'dispatch': function (route, options) {
			var controlerId = '',
				$controler = null,
				defaultControllerId = 'gacd-page-controller', // @todo check if this variable is still used
				parameters = [];

			// The controllerId is given
			if (typeof (options.controllerId) != 'undefined') {
				controllerId = options.controllerId;
			} else { // Else find it from the route
				controllerId = 'js_' + route.extension + '_' + route.controller + '_controller';
			}

			var pluginNameController = mad.helper.routeHelper.pluginNameController(route, {
				'prefix': route.extension != 'passbolt' ? 'passbolt_' : ''
			});
			// Find the controller element on the page
			$controller = $('#' + controllerId);

			// check the target controller has a representative DOM node
			if (!$controller.length) {
				// if the controller has not a representative DOM node, check if a default controller id has been given to locate the controller DOM node
				$controller = $('#' + defaultControllerId);
				if (!$controller.length) {
					throw new Error('No element found on the page for the id (' + controllerId + ') neither with the default id (' + defaultControllerId + ')');
				}
			}

			// check the controller element has a reference to the plugin controller
			if (typeof $controller[pluginNameController] == 'undefined') {
				throw new Error('The DOM element (' + controllerId + ') has not an attached controller defined by the class (' + mad.helper.routeHelper.classNameController(route) + ')');
			}

			// if the controller has not yet been instantiated, instantiate it
			var controllers = $controller.data("controllers");
			if (typeof controllers == 'undefined') {
				$controller[pluginNameController]();
			}

			// extract parameters
			for (var i = 1; true; i++) {
				if(typeof route['p' + i] === 'undefined') break; // For you Remy the future PIO
				parameters.push(route['p' + i]);
			}

			// call the action's controler
			//                $controller[pluginNameController](route.action);
			var controller = $controller.controller();
			controller[route.action].apply(controller, parameters);
		}
	}, /** @prototype */ { });

});