steal(
	'mad/view/component/tree.js',
	'app/view/component/resourceDetails.js',
  'app/controller/component/commentsController.js'
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
			'viewClass': passbolt.view.component.ResourceDetails,
			// the resource to bind the component on
			'resource': null,
			// the selected resources, you can pass an existing list as parameter of the constructor to share the same list
			'selectedRs': new can.Model.List()
		}

	}, /** @prototype */ {
		
		/**
		 * before start hook.
		 * @return {void}
		 */
		'beforeRender': function() {
			this._super();
			// pass the new resource to the view
			this.setViewData('resource', this.options.resource);
		},

		/**
		 * Called right after the start function
		 * @return {void}
		 * @see {mad.controller.ComponentController}
		 */
		'afterStart': function() {
			// Instantiate the comments controller
			var commentsController = new passbolt.controller.component.CommentsController($('#js_rs_details_comments', this.element), {
				'resource': this.options.resource
			});
			commentsController.start();
		},
		
		/**
		 * Load details of a resource
		 * @param {passbolt.model.Resource} resource The resource to load
		 * @return {void}
		 */
		'load': function (resource) {
			// push the new resource in the options to be able to listen the resource
			// change in the function name
			this.options.resource = resource;
			// If the component has not been already started
			if(this.state.is(null)) {
				this.start();
			} else {
				// refresh the component -> afterStart
				this.refresh();
			}
			// Some options changed, make the controller able to listen changes on this new options
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
		/* ************************************************************** *//**
		 * Observe when a resource is selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The selected resource
		 * @return {void}
		 */
		'{selectedRs} add': function (el, ev, resource) {
			// if more than one resource selected, or no resource selected
			if (this.options.selectedRs.length == 0 || this.options.selectedRs.length > 1) {
				this.options.resource = null;
				this.setState('hidden');
				
			// else if only 1 resource selected show the details
			} else {
				// load the only one resource
				this.options.resource = this.options.selectedRs[0];
				this.load(this.options.resource);
				this.setState('ready');
			}
		},

		/**
		 * Observe when a resource is unselected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The unselected resource
		 * @return {void}
		 */
		'{selectedRs} remove': function (el, ev, resource) {
			// if more than one resource selected, or no resource selected
			if (this.options.selectedRs.length == 0 || this.options.selectedRs.length > 1) {
				this.options.resource = null;
				this.setState('hidden');
				
			// else if only 1 resource selected show the details
			} else {
				// load the only one resource
				this.options.resource = this.options.selectedRs[0];
				this.load(this.options.resource);
				this.setState('ready');
			}
		}

	});

});