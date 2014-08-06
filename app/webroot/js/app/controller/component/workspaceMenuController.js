steal(
    'mad/controller/componentController.js',
	'app/view/template/component/workspaceMenu.ejs'
).then(function () {

	/*
	 * @class passbolt.controller.component.WorkspaceMenuController
	 * @inherits mad.controller.component.ComponentController
	 * @parent index 
	 * 
	 * Our passbolt workspace menu controller
	 * 
	 * @constructor
	 * Creates a new workspace menu controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.WorkspaceMenuController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.WorkspaceMenuController', /** @static */ {

		'defaults': {
			'label': 'Workspace Menu Controller',
			// the selected resources, you can pass an existing list as parameter of the constructor to share the same list
			'selectedRs': new can.Model.List()
		}

	}, /** @prototype */ {

		/**
		 * after start hook.
		 * @return {void}
		 */
		'afterStart': function () {
			// Manage creation action 
			this.options.creationButton = new mad.controller.component.ButtonController($('#js_wk_menu_creation_button'))
				.start();
			
			// Manage edition action 
			this.options.editionButton = new mad.controller.component.ButtonController($('#js_wk_menu_edition_button'), {
				'state': 'disabled'
			}).start();
			
			// Manage deletion action 
			this.options.deletionButton = new mad.controller.component.ButtonController($('#js_wk_menu_deletion_button'), {
				'state': 'disabled'
			}).start();

			// Manage sharing action 
			this.options.sharingButton = new mad.controller.component.ButtonController($('#js_wk_menu_sharing_button'), {
				'state': 'disabled'
			}).start();

			// Manage more action 
			this.options.moreButton = new mad.controller.component.ButtonDropdownController($('#js_wk_menu_more_button'), {
				'state': 'disabled'
			}).start();
			
			// Rebind controller events
			this.on();
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */
		
		/**
		 * Observe when the user wants to create a new instance (Resource, User depending of the active workspace)
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{creationButton} click': function (el, ev) {
			var categories = this.options.creationButton.getValue();
			mad.bus.trigger('request_resource_creation', categories);	
		},

		/**
		 * Observe when the user wants to edit an instance (Resource, User depending of the active workspace)
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{editionButton} click': function (el, ev) {
			var category = this.options.editionButton.getValue();
			mad.bus.trigger('request_resource_edition', category);	
		},

		/**
		 * Observe when the user wants to delete an instance (Resource, User depending of the active workspace)
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{deletionButton} click': function (el, ev) {
			var category = this.options.deletionButton.getValue();
			mad.bus.trigger('request_resource_deletion', category);	
		},

		/**
		 * Observe when the user wants to share an instance (Resource, User depending of the active workspace)
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{sharingButton} click': function (el, ev) {
			var category = this.options.sharingButton.getValue();
			mad.bus.trigger('request_resource_sharing', category);	
		},
		
		/**
		 * Observe when a resource is selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The selected resource
		 * @return {void}
		 */
		'{selectedRs} add': function (el, ev, resource) {
			// if more than one resource selected, or no resource selected
			if (this.options.selectedRs.length == 0) {
				this.setState('ready');
			
			// else if only 1 resource selected show the details
			} else if (this.options.selectedRs.length == 1) {
				this.setState('selection');
			
			// else if more than one resource have been selected
			} else {
				this.setState('multiSelection');
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
			if (this.options.selectedRs.length == 0) {
				this.setState('ready');
			
			// else if only 1 resource selected show the details
			} else if (this.options.selectedRs.length == 1) {
				this.setState('selection');
			
			// else if more than one resource have been selected
			} else {
				this.setState('multiSelection');
			}
		},

		/**
		 * Observe when a filter is applied on the wsp
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Filter} filter The unselected resource
		 * @return {void}
		 */
		'{mad.bus} filter_resources_browser': function(el, ev, filter) {
			var categories = can.List([]);
			var filterCategories = filter.getForeignModels('Category');
			if(filterCategories) {
				categories = filterCategories;
			}
			this.options.creationButton.setValue(categories);
		},

		/* ************************************************************** */
		/* LISTEN TO THE STATE CHANGES */
		/* ************************************************************** */

		/**
		 * Listen to the change relative to the state selected
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateSelection': function (go) {
			if (go) {
				this.options.editionButton
					.setValue(this.options.selectedRs[0])
					.setState('ready');
				this.options.deletionButton
					.setValue(this.options.selectedRs)
					.setState('ready');
				this.options.sharingButton
					.setValue(this.options.selectedRs)
					.setState('ready');
				this.options.moreButton
					.setValue(this.options.selectedRs[0])
					.setState('ready');
			} else {
				this.options.editionButton
					.setValue(null)
					.setState('disabled');
				this.options.deletionButton
					.setValue(null)
					.setState('disabled');
				this.options.sharingButton
					.setValue(null)
					.setState('disabled');
				this.options.moreButton
					.setValue(null)
					.setState('disabled');
			}
		},

		/**
		 * Listen to the change relative to the state multiSelection
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateMultiSelection': function (go) {
			if (go) {
				this.options.editionButton
					.setState('disabled');
				this.options.deletionButton
					.setValue(this.options.selectedRs)
					.setState('ready');
				this.options.sharingButton
					.setValue(this.options.selectedRs)
					.setState('ready');
				this.options.moreButton
					.setState('disabled');
			} else {
				this.options.editionButton
					.setValue(null)
					.setState('disabled');
				this.options.deletionButton
					.setValue(null)
					.setState('disabled');
				this.options.sharingButton
					.setValue(null)
					.setState('disabled');
			}
		}

	});

});