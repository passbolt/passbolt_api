steal(
	'mad/view'
).then(function () {

	/*
	 * @class passbolt.view.component.PasswordsActionsMenu
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('passbolt.view.component.PasswordsActionsMenu', /** @static */ {

	}, /** @prototype */ {

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the user wants to create a new resource
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'#js_request_resource_creation_button click': function (el, ev) {
			var category = el.controller().getValue();
			mad.bus.trigger('request_resource_creation', category);
		},

		/**
		 * Observe when the user wants to edit a resource
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'#js_request_resource_edition_button click': function (el, ev) {
			var resources = el.controller().getValue();
			mad.bus.trigger('request_resource_edition', resources);
		},

		/**
		 * Observe when the user wants to delete a resource
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'#js_request_resource_deletion_button click': function (el, ev) {
			var resources = el.controller().getValue();
			mad.bus.trigger('request_resource_deletion', resources);
		},

		/**
		 * Observe when the user wants to share a resource
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'#js_request_resource_sharing_button click': function (el, ev) {
			var resources = el.controller().getValue();
			mad.bus.trigger('request_resource_sharing', resources);
		}

	});
});