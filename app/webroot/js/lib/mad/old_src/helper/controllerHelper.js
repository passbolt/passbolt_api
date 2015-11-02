steal(
	'can/construct'
).then(function () {

	/**
	 * The controller class helper offers to the developper tools arround controllers
	 */
	can.Construct('mad.helper.ControllerHelper', /** @static */ {

		/**
		 * Get view path of a controller.
		 * <br/>
		 * This function can determine view path for controllers from :
		 * <br/>
		 * <ul>
		 *   <li>the mad squirrel library itself</li>
		 *   <li>the application</li>
		 *   <li>the plugins of the application</li>
		 * </ul>
		 * <br/><br/>
		 * For controllers from the mad squirrel library (mad.controller.component.ContainerController):
		 * <br/>
		 * mad/view/template/component/containerController.ejs
		 * <br/><br/>
		 * For controllers from the application (passbolt.controller.PasswordWorkspaceController):
		 * <br/>
		 * app/view/template/passwordWorkspaceController.ejs
		 * <br/><br/>
		 * For controllers from the plugins of the application (passbolt.activity.controller.activityWorkspaceController):
		 * <br/>
		 * app/plugin/activity/view/template/activityWorkspaceController.ejs
		 *
		 * @param {jQuery.Controller} clazz Controller to determine the view path
		 * @param {array} options
		 * @param {boolean} options.check @deprecated Check if the path exists
		 * @return {string} Return the view path of the given controller. If the options.check is set to
		 * true and the view template does not exist return an empty string
		 */
		'getViewPath': function (clazz, options) {
			var returnValue = '';

			var clazzName = clazz.fullName;
			var split = clazzName.split('.');

			// extract the controller name, and treat it to find its view name
			var controllerName = split.pop();
			var viewName = $.String.camelize(controllerName.substr(0, controllerName.indexOf('Controller')));

			// extract namespace
			if (split[0] == 'mad') {
				returnValue += 'mad';
				split = split.splice(1);
			} else if (split[0] == mad.Config.read('app.namespace')) {
				//we are in a plugin
				if (split[1] != 'controller') {
					returnValue += 'app/plugin/' + split[1];
					split = split.splice(2);
				} else {
					//else we are in the application
					returnValue += 'app';
					split = split.splice(1);
				}
			}
			//the next in the split has to be the controller, else there is an error in the controller name
			if (split[0] != 'controller') {
				throw new mad.error.Exception('Controller name mal formed');
			}
			split = split.splice(1);

			// target the view folder
			returnValue += '/view/template/';
			if (split.length) returnValue += split.join('/') + '/';

			// add the view name (et voila batard)
			returnValue += viewName + '.ejs';

			return returnValue;
		}

//		// @todo copy/page of getViewPath, make something more proper
//		// @deprecated or not
//		'getView': function (clazz, options) {
//			alert('oh');
//			var returnValue = '';
//
//			var clazzName = clazz.fullName;
//			var split = clazzName.split('.');
//
//			// extract the controller name, and treat it to find its view name
//			var controllerName = split.pop();
//			var viewName = $.String.camelize(controllerName.substr(0, controllerName.indexOf('Controller')));
//
//			// extract namespace
//			if (split[0] == 'mad') {
//				returnValue += 'mad';
//				split = split.splice(1);
//			} else if (split[0] == mad.controller.AppController.getGlobal('APP_NS_ID')) {
//				//we are in a plugin
//				if (split[1] != 'controller') {
//					returnValue += 'plugin.' + split[1];
//					split = split.splice(2);
//				}
//				//else we are in the application
//				else {
//					returnValue += mad.controller.AppController.getGlobal('APP_NS_ID');
//					split = split.splice(1);
//				}
//			}
//
//			//the next in the split has to be the controller, else there is an error in the controller name
//			if (split[0] != 'controller') {
//				throw new mad.error.Exception('Controller name mal formed');
//			}
//			split = split.splice(1);
//
//			// target the view folder
//			returnValue += '.view.';
//			if (split.length) returnValue += split.join('.') + '.';
//
//			// add the view name (et voila batard)
//			returnValue += $.String.capitalize(viewName);
//
//			var view = $.String.getObject(returnValue, null);
//			return view;
//		}

	}, /** @prototype */ {}
);

});