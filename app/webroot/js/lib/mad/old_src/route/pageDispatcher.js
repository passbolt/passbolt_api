steal(
	'mad/route/extensionControllerActionDispatcher.js'
).then(function () {

	/*
	 * @class mad.route.PageDispatcher
	 * @inherits mad.route.ExtionsControllerActionDispatcher
	 * @parent mad.route
	 * 
	 * The page dispatcher is the common way to dispatch actions in page. Here the controller which uses 
	 * this dispatcher is a page.
	 * <br/>
	 * This page dispatcher has to be a little bit refactored because of hard value !
	 * Document it later, when the refactoring is ok.
	 */
	mad.route.ExtionsControllerActionDispatcher.extend('mad.route.PageDispatcher', /** @static */ {

		/**
		 * Dispatch the route to the convenient actions
		 * @param {mad.net.Route} route The route to disptach to
		 * @param {array} options Optional parameters
		 */
		'dispatch': function (route, options) {
			var controllers;
			var pluginNameController = mad.helper.routeHelper.pluginNameController(route);

			// The controllerId is given
			if (typeof (options.ControllerClass) == 'undefined') {
				throw new Error("mad.error.ExtensionControllerActionDispatcher error : The options.ControllerClass has to be defined");
			}

			var $pageController = $('#gacd-page-controller');
			// if a page has yet been loaded
			controllers = $pageController.data("controllers");
			if (typeof controllers != 'undefined') {
				// if the current controller is not the targeted one
				if (typeof controllers[pluginNameController] == 'undefined') {
					// destroy the old one
					for (var pluginNameController in controllers) {
						$pageController[pluginNameController]("destroy");
					}
					// build the new one
					new options.ControllerClass($pageController);
				}
			}
			// no controller = no page, create the new one
			else {
				new options.ControllerClass($pageController);
			}

			options.controllerId = 'gacd-page-controller';
			this._super(route, options);
		}
		
	}, /** @prototype */ { });

});