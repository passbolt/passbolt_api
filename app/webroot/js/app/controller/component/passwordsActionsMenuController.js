steal(
	'mad/view/component/tree.js',
	'app/view/component/passwordsActionsMenu.js',
	'app/view/template/component/passwordsActionsMenu.ejs'
).then(function () {

	/*
	 * @class passbolt.controller.component.PasswordsActionsMenuController
	 * @inherits mad.controller.component.TreeController
	 * @parent index 
	 * 
	 * @constructor
	 * Creates a new passwords actions menu controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.PasswordsActionsMenuController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.PasswordsActionsMenuController', /** @static */ {

		'defaults': {
			'label': 'Passwords Actions Menu Controller',
			'viewClass': passbolt.view.component.PasswordsActionsMenu,
			// the selected resources, you can pass an existing list as parameter of the constructor to share the same list
			'selectedRs': new can.Model.List()
		}

	}, /** @prototype */ {

		'render': function () {
			this._super();
			var creationBt = new mad.controller.component.ButtonController('#js_request_resource_creation_button');
			var editionBt = new mad.controller.component.ButtonController('#js_request_resource_edition_button', {'state': 'disabled'});
			var deletionBt = new mad.controller.component.ButtonController('#js_request_resource_deletion_button', {'state': 'disabled'});
			var sharingBt = new mad.controller.component.ButtonController('#js_request_resource_sharing_button', {'state': 'disabled'});
			var modeBt = new mad.controller.component.ButtonController('#js_request_resource_more_button', {'state': 'disabled'});
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */
		
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
				mad.app.getComponent('js_request_resource_edition_button')
					.setValue(this.options.selectedRs[0])
					.setState('ready');
				mad.app.getComponent('js_request_resource_deletion_button')
					.setValue(this.options.selectedRs)
					.setState('ready');
				mad.app.getComponent('js_request_resource_sharing_button')
					.setValue(this.options.selectedRs)
					.setState('ready');
				mad.app.getComponent('js_request_resource_more_button')
					.setValue(this.options.selectedRs[0])
					.setState('ready');
			} else {
				mad.app.getComponent('js_request_resource_edition_button')
					.setValue(null)
					.setState('disabled');
				mad.app.getComponent('js_request_resource_deletion_button')
					.setValue(null)
					.setState('disabled');
				mad.app.getComponent('js_request_resource_sharing_button')
					.setValue(null)
					.setState('disabled');
				mad.app.getComponent('js_request_resource_more_button')
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
				mad.app.getComponent('js_request_resource_edition_button')
					.setState('disabled');
				mad.app.getComponent('js_request_resource_deletion_button')
					.setValue(this.options.selectedRs)
					.setState('ready');
				mad.app.getComponent('js_request_resource_sharing_button')
					.setValue(this.options.selectedRs)
					.setState('ready');
				mad.app.getComponent('js_request_resource_more_button')
					.setState('disabled');
			} else {
				mad.app.getComponent('js_request_resource_edition_button')
					.setValue(null)
					.setState('disabled');
				mad.app.getComponent('js_request_resource_deletion_button')
					.setValue(null)
					.setState('disabled');
				mad.app.getComponent('js_request_resource_sharing_button')
					.setValue(null)
					.setState('disabled');
			}
		}

	});

});