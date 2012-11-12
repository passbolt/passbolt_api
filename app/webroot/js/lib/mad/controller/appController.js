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
	 * This class is by definition a singleton.
	 */
	mad.controller.ComponentController.extend('mad.controller.AppController', /** @static */ {

		/**
		 * The application namespace
		 * @static
		 */
		'APP_NS_ID': null,

		/**
		 * @static
		 * Reference global variables
		 * @type {array}
		 */
		'globals': [],

		/**
		 * @static
		 * get global
		 * @param {string} name Name of the variable 
		 * @return {mixed} Value of the variable 
		 */
		'getGlobal': function (name) {
			if (mad.controller.AppController.APP_NS_ID == null) {
				throw new mad.error.Exception('The application namespace is not initialized');
			}
			return window[mad.controller.AppController.APP_NS_ID][name];
		},

		/**
		 * @static
		 * delete global
		 * @param {string} name Name of the variable 
		 * @return {void}
		 */
		'deleteGlobal': function (name) {
			if (mad.controller.AppController.APP_NS_ID == null) {
				throw new mad.error.Exception('The application namespace is not initialized');
			}

			delete window[mad.controller.AppController.APP_NS_ID][name]; // delete the global variable
			var position = $.inArray(name, mad.controller.AppController.globals); // delete the reference of the global
			if (position == -1) {
				throw new Error('The global variable (' + name + ') has not well been referenced');
			}
			delete mad.controller.AppController.globals[position];
		},

		/**
		 * @static
		 * set global
		 * @param {string} name Name of the variable 
		 * @param {mixed} value Value of the variable 
		 * @return {void}
		 */
		'setGlobal': function (name, value) {
			if (mad.controller.AppController.APP_NS_ID == null) {
				throw new mad.error.Exception('The application namespace is not initialized');
			}
			mad.controller.AppController.globals.push(name); // reference the name of the variable
			window[mad.controller.AppController.APP_NS_ID][name] = value; // store the variable
			return window[mad.controller.AppController.APP_NS_ID][name]; // return the variable to allow chaining
		},

		/**
		 * @static
		 * Init the application namespace by :
		 * <br/>
		 * <ul>
		 *   <li>by creating the namespace if this one does not exist</li>
		 *   <li>by populating the variable mad.controller.AppController.APP_NS_ID</li>
		 *   <li>by making alias of APP_NS_ID and APP_NS on the namespace mad</li>
		 *   <li>by making alias of getGlobal and setGlobal on the application namespace</li>
		 * </ul>
		 * @param {string} appNsId The ns id
		 * @return {void}
		 */
		'setNs': function (appNsId) {
			mad.controller.AppController.APP_NS_ID = appNsId;

			//If the application namespace does not exist yet create it
			if (typeof window[mad.controller.AppController.APP_NS_ID] == 'undefined') {
				window[mad.controller.AppController.APP_NS_ID] = {};
			}
			//If the application namespace has yet been populated, something is wrong ... throw an error
			if (typeof window[mad.controller.AppController.APP_NS_ID].APP_NS_ID != 'undefined') {
				throw new mad.error.Exception('The application namespace has yet been initialized');
			}
			//make global variables with the ns variables
			mad.controller.AppController.setGlobal('APP_NS_ID', mad.controller.AppController.APP_NS_ID);
			mad.controller.AppController.setGlobal('APP_NS', window[mad.controller.AppController.APP_NS_ID]);
			//make aliases
			mad.APP_NS_ID = mad.controller.AppController.getGlobal('APP_NS_ID');
			mad.APP_NS = mad.controller.AppController.getGlobal('APP_NS');
			// make aliases on the app namespace with the functions set and get globals
			mad.controller.AppController.getGlobal('APP_NS').getGlobal = mad.controller.AppController.getGlobal;
			mad.controller.AppController.getGlobal('APP_NS').setGlobal = mad.controller.AppController.setGlobal;
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
			// make the application global var
			mad.setGlobal('app', this);
			// make an alias in the mad lib
			mad.app = mad.getGlobal('app');
			
			this._super(el, options);
		},

		/**
		 * Reference a component to the application.
		 * @param {mad.controller.ComponentController} component The component to reference
		 * @return {void}             * 
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

		/**
		 * Called when the application is ready            
		 * @event {APP_NS_ID_app_ready}
		 * @return {void}
		 */
		'ready': function () {
			mad.eventBus.trigger(mad.APP_NS_ID + '_application_ready');
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		// @debug
		'{mad.eventBus} {mad.APP_NS_ID}_controller_released': function (element, evt, data) {
			//			steal.dev.log(__('new controller (%s) instance of (%s) has been released', data.component.getId(), data.component.Class.fullName));
		}

	});

	// Bon a la fin de la classe comme ca, c'est un peu laid
	mad.controller.AppController.augment('mad.core.Singleton');

	// make aliases with some function
	mad.getGlobal = mad.controller.AppController.getGlobal;
	mad.setGlobal = mad.controller.AppController.setGlobal;
});