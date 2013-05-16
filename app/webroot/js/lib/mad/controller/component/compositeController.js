steal(
	'mad/controller/componentController.js'
).then(function () {

	/*
	 * @class mad.controller.component.CompositeController
	 * @inherits mad.controller.ComponentController
	 * @parent mad.controller.component
	 * 
	 * Our implementation of the Composite Component
	 * 
	 * @constructor
	 * Create a composite controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {mad.controller.component.CompositeController}
	 */
	mad.controller.ComponentController.extend('mad.controller.component.CompositeController', /** @static */ {

		'defaults': {
			'label': 'Composite Component Controller'
		}

	}, /** @prototype */ {

		// constructor like
		'init': function(el, options) {
			/**
			 * Components carried by the composite
			 * @type {Array}
			 */
			this._components = [];
			
			this._super(el, options);
		},
		
		/**
		 * Get a component
		 * @param {string} id Id of the target component
		 * @return {mad.controller.ComponentController}
		 */
		'getComponent': function (id) {
			return this._components[id];
		},

		/**
		 * Add a component to the container
		 * @param {mad.controller.ComponentController} component The component to add to the composite
		 * @return {{mad.controller.ComponentController}} the added component
		 */
		'addComponent': function (component) {
			this._components[component.getId()] = component;
			return component;
		}

	});

});