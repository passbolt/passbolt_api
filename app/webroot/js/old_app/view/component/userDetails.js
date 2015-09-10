steal(
	'mad/view'
).then(function () {

	/*
	 * @class passbolt.view.component.UserDetails
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('passbolt.view.component.UserDetails', /** @static */ {

	}, /** @prototype */ {

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the user clicks on the close button
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'.icon.close click': function(el, ev) {
			mad.Config.write('ui.workspace.showSidebar', false);
			mad.bus.trigger('workspace_showSidebar', false);
		},

		/**
		 * Observe when the user clicks on the copy key button.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'a.copy-public-key click': function (el, ev) {
			ev.stopPropagation();
			ev.preventDefault();
			this.element.trigger('request_copy_publickey', [ev]);
		}
	});
});