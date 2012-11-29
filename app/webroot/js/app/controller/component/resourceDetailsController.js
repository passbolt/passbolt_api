steal(
	'mad/view/component/tree.js',
	'app/view/component/resourceDetails.js'
).then(function () {

	/*
	 * @class passbolt.controller.ResourceDetailsController
	 * @inherits mad.controller.component.ComponentController
	 * @parent index 
	 * 
	 * @constructor
	 * Creates a new Resource details controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.ResourceDetailsController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.ResourceDetailsController', /** @static */ {

		'defaults': {
			'label': 'Resource Details Controller',
			// the resource to bind the component on
			'resource': null,
			'viewClass': passbolt.view.component.ResourceDetails
		}

	}, /** @prototype */ {

		/**
		 * Load details of a resource
		 * @param {passbolt.model.Resource} resource The resource to load
		 * @return {void}
		 */
		'load': function (resource) {
			if (this.state.is('hidden')) {
				this.setState('ready');
			}

			// push the new resource in the options to be able to listen the resource
			// change in the function name
			this.options.resource = resource;
			// pass the new resource to the view
			this.setViewData('resource', resource);
			// refresh the view
			this.refresh();
			// rebind the controller listeners
			this.on();
		},

		/* ************************************************************** */
		/* LISTEN TO THE MODEL EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when an resource is updated
		 * @param {passbolt.model.Resource} resource The updated resource
		 * @return {void}
		 */
		'{resource} updated': function (resource) {
			// The reference of the resource does not change, refresh the component
			this.refresh();
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when an resource is selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The selected resource instance
		 * @return {void}
		 */
		'{mad.bus} resource_selected': function (element, event, resource) {
			this.load(resource);
		},

		/**
		 * Observe when an resource is unselected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The selected resource instance
		 * @return {void}
		 */
		'{mad.bus} resource_unselected': function (element, event, resource) {
			// ubind the current resource to avoid any troubles
			this.options.resource = null;
			this.on();
		}

	});

});