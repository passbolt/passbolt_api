steal(
	'mad/view/component/tree.js',
	'app/view/component/passwordsActionsMenu.js'
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
			'viewClass': passbolt.view.component.PasswordsActionsMenu
//			'templateUri': 'app/view/html5/template/controller/component/passwordsActionsMenu.ejs'
		}

	}, /** @prototype */ {

		'render': function () {
			this._super();
			new mad.controller.component.ButtonController('#js_request_resource_creation_button');
			new mad.controller.component.ButtonController('#js_request_resource_edition_button', {'state': 'hidden'});
			new mad.controller.component.ButtonController('#js_request_resource_deletion_button', {'state': 'hidden'});
			new mad.controller.component.ButtonController('#js_request_resource_sharing_button', {'state': 'hidden'});
			new mad.controller.component.ButtonController('#js_request_resource_more_button', {'state': 'hidden'});
		},

		/**
		 * Observe when a resource is unselected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The unselected resource
		 * @return {void}
		 */
		'{passbolt.eventBus} resource_unselected': function (el, ev, resource) {
			mad.app.getComponent('js_request_resource_edition_button').setState('hidden');
			mad.app.getComponent('js_request_resource_deletion_button').setState('hidden');
			mad.app.getComponent('js_request_resource_sharing_button').setState('hidden');
			mad.app.getComponent('js_request_resource_more_button').setState('hidden');
		},

		/**
		 * Observe when category is selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Category} category The selected category
		 * @return {void}
		 */
		'{passbolt.eventBus} category_selected': function (el, ev, category) {
			mad.app.getComponent('js_request_resource_creation_button').setValue(category);
		},

		/**
		 * Observe when a resource is selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The selected resource
		 * @return {void}
		 */

		'{passbolt.eventBus} resource_selected': function (el, ev, resource) {
			mad.app.getComponent('js_request_resource_edition_button').setValue(resource).setState('ready');
			mad.app.getComponent('js_request_resource_deletion_button').setValue(resource).setState('ready');
			mad.app.getComponent('js_request_resource_sharing_button').setValue(resource).setState('ready');
			mad.app.getComponent('js_request_resource_more_button').setValue(resource).setState('ready');
		}

	});

});