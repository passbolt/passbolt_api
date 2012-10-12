steal(
	MAD_ROOT + '/view/component/tree.js'
).then(function ($) {

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
			'label': 'Resource Details Controller'
		},
		'listensTo': []

	}, /** @prototype */ {

		'init': function (el, options) {
			this._super(el, options);
			this.setViewData('resource', new passbolt.model.Resource());
			this.render();
		},

		/**
		 * Load details of a resource
		 * @param {passbolt.model.Resource} resource The resource to load
		 * @return {void}
		 */
		'load': function (resource) {
			this.setViewData('resource', resource);
			this.refresh();
			// The controller will be destroyed after the refresh ... empty => delete node => destroy controller
			// We do not need to create button here, but if we need to manage state, for the share button we will
			// do like that
			var copyLoginButton = new mad.controller.component.ButtonController($('#js_details_copy_login_button', this.element), {
				'value': resource.Resource.id
			}).render();

			var copySecretButton = new mad.controller.component.ButtonController($('#js_details_copy_secret_button', this.element), {
				'value': resource.Resource.id
			}).render();

			var oneClickLoginButton = new mad.controller.component.ButtonController($('#js_details_one_click_login_button', this.element), {
				'value': resource.Resource.id
			}).render();

			var shareButton = new mad.controller.component.ButtonController($('#js_details_share_button', this.element), {
				'value': resource.Resource.id
			}).render();
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Listen to the click on the h2 event, rolldown the following p tag
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'h2 click': function (element, event) {
			element.next('p').toggle();
		},

		/**
		 *
		 */
		'#js_copy_login_button click': function (element, event, resourceId) {
			mad.eventBus.trigger('copy_login_clipboard', resourceId);
		},

		/**
		 *
		 */
		'#js_copy_secret_button click': function (element, event, resourceId) {
			mad.eventBus.trigger('copy_secret_clipboard', resourceId);
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when a resource is selected
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQury event
		 * @param {string} resourceId The selected Resource id
		 * @return {void}
		 */
		'{passbolt.eventBus} resource_selected': function (element, event, resource) {
			var self = this;
			this.crtResourceId = resource.id;
			this.setState('loading');

			passbolt.model.Resource.get({
				id: resource.id
			}, function (request, response, rs) {
				if (self.crtResourceId != request.data.id) {
					steal.dev.log('(OutOfDate) Cancel passbolt.model.Resource.get request callback in passbolt.controller.component.ResourceDetailsController');
					return;
				}
				self.load(rs);
				self.setState('ready');
			});
		}

	});

});