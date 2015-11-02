steal(
	'can/construct'
).then(function () {

	can.Construct('mad.helper.routeHelper', {

		'getClassController': function () {

		},

		'formatStrToPluginName': function (str) {
			var returnValue = '';

			// change uppercase char with _ following by the char in lowercase
			var j = 0;
			for (var i in str) {
				if (str[i] != '_' && str[i] == str[i].toUpperCase()) {
					returnValue += '_' + str[i].toLowerCase();
					j += 1;
				} else {
					returnValue += str[i];
				}
				j++;
			}

			return returnValue;
		},

		/**
		 * Get a plugin name controller functions of a route
		 * @param {lb.core.models.Route} route
		 * @static
		 * @todo set the application name. Now gacd is currently used, and clean the param
		 * @todo move a part in classHelper
		 */
		'pluginNameController': function (route, options) {
			var returnValue = '';
			var prefix = typeof (options) != 'undefined' && typeof (options.prefix) != 'undefined' ? options.prefix : '';

			//route is route :)
			if(typeof route == 'object') {
				returnValue = route.extension + '_' + route.controller;
			}
			//is a string which represent de class
			else {
				var arr = route.split('.');
				//returnValue = arr[0] + '_' + arr[1] + '_' + arr[3].substr(0,1).toLowerCase() + arr[3].substr(1, arr[3].length-11);
				//myAppName.myExtensionName.controller.myCtlNameController -> myAppName_myExtensionName_myCtlName
				returnValue = arr[0] + '_' + arr[1] + '_' + arr[3].substr(0, arr[3].length - 10).toLowerCase();
			}

			return prefix + mad.helper.routeHelper.formatStrToPluginName(returnValue);
		}

		/**
		 * Get a class name controller functions of a route
		 * @param {lb.core.models.Route} route
		 * @static
		 * @todo set the application name. Now gacd is currently used
		 */
		,
		'classNameController': function (route, options) {
			var prefix = typeof (options) != 'undefined' && typeof (options.prefix) != 'undefined' ? options.prefix : '';
			var controllerName = route.controller.charAt(0).toUpperCase() + route.controller.slice(1) + 'Controller';
			return prefix + route.extension + '.controller.' + controllerName;
		}
	}, {
		/**
		 * There is no constructor
		 * @exception lb.core.NoConstructor
		 */
		'init': function () {
			throw new lb.core.NoConstructor();
		}
	});

});