steal(
	'mad/view/component/tree.js'
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
			'resource': null
		}

	}, /** @prototype */ {

		/**
		 * Load details of a resource
		 * @param {passbolt.model.Resource} resource The resource to load
		 * @return {void}
		 */
		'load': function (resource) {
			if (this.state.is('hidden')) {
				this.setState('ready')
			}

			// push the new resource in the options to be able to listen the resource
			// change in the function name
			this.options.resource = resource;
			// pass the new resource to the view
			this.setViewData('resource', resource);
			// refresh the view
			this.refresh();
			// rebind the listener
			this.on();
			
//			// We do not need to create button here, but if we need to manage state, for the share button we will
//			// do like that
//			var copyLoginButton = new mad.controller.component.ButtonController($('#js_details_copy_login_button', this.element), {
//				'value': resource.id
//			}).render();
//
//			var copySecretButton = new mad.controller.component.ButtonController($('#js_details_copy_secret_button', this.element), {
//				'value': resource.id
//			}).render();
//
//			var oneClickLoginButton = new mad.controller.component.ButtonController($('#js_details_one_click_login_button', this.element), {
//				'value': resource.id
//			}).render();
//
//			var shareButton = new mad.controller.component.ButtonController($('#js_details_share_button', this.element), {
//				'value': resource.id
//			}).render();
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the user clicks on the h2 event, rolldown the following p tag
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'h2 click': function (el, ev) {
			el.next('p').toggle();
		},

		/**
		 *
		 */
		'#js_copy_login_button click': function (element, event) {
//			mad.eventBus.trigger('copy_login_clipboard', resourceId);
		},

		/**
		 *
		 */
		'#js_copy_secret_button click': function (element, event) {
//			mad.eventBus.trigger('copy_secret_clipboard', resourceId);
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
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

		/**
		 * Observe when an resource is selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The selected resource instance
		 * @return {void}
		 */
		'{passbolt.eventBus} resource_selected': function (element, event, resource) {
			this.load(resource);
		},

		/**
		 * Observe when an resource is unselected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The selected resource instance
		 * @return {void}
		 */
		'{passbolt.eventBus} resource_unselected': function (element, event, resource) {
			// ubind the current resource to avoid any troubles
			this.options.resource = null;
			this.on();
		}

	});

});