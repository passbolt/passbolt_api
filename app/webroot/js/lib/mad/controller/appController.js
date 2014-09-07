steal(
	'mad/controller/componentController.js',
	'mad/view/template/app.ejs'
).then(function () {

	/*
	 * @class mad.controller.AppController
	 * @inherits mad.controller.ComponentController
	 * @parent mad.core
	 * 
	 * The main application controller.
	 */
	mad.controller.ComponentController.extend('mad.controller.AppController', /** @static */ {

		'defaults': {
			// 'templateBased': false
		},

		/**
		 * Reset the application controller
		 * @return {void}
		 */
		'destroy': function () {
			// if the namespace has not been populated
			if (mad.controller.AppController.APP_NS_ID == null) {
				return;
			}

			// delete globals
			for (var i in mad.controller.AppController.globals) {
				var name = mad.controller.AppController.globals[i];
				if (name == 'APP_NS') continue; // This is a reference to itselef, delete it after
				mad.controller.AppController.deleteGlobal(name)
			}

			mad.controller.AppController.deleteGlobal('APP_NS');
			delete window[mad.controller.AppController.APP_NS_ID];

			//delete aliases
			delete mad.APP_NS_ID;
			delete mad.APP_NS;

			mad.controller.AppController.APP_NS_ID = null;
		}

	}, /** @prototype */ {
		
		/**
		 * Reference to application's components
		 * @type mad.controller.ComponentController
		 */
		'_components': {},

		// constructor like
		'init': function (el, options) {
			mad.app = this;
			this._super(el, options);
		},

		/**
		 * Reference a component to the application.
		 * @param {mad.controller.ComponentController} component The component to reference
		 * @return {void}
		 */
		'referenceComponent': function (component) {
			this._components[component.getId()] = component;
		},

		/**
		 * Unreference a component to the application.
		 * @param {mad.controller.ComponentController} component The component to unreference
		 * @return {void}
		 */
		'unreferenceComponent': function (component) {
			delete this._components[component.getId()];
		},

		/**
		 * Get a referenced component
		 * @param {String} componentId Id of the target component
		 * @return {mad.controller.ComponentController}
		 */
		'getComponent': function (componentId) {
			var returnValue = null;
			if (typeof this._components[componentId] != 'undefined') {
				returnValue = this._components[componentId];
			}
			return returnValue;
		},

		/* ************************************************************** */
		/* LISTEN TO THE STATE CHANGES */
		/* ************************************************************** */

		/**
		 * The application is ready.
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateReady': function (go) {
			mad.bus.trigger('app_ready');
		}

	});
});
