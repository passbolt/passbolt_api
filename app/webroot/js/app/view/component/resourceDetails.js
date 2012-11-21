steal(
	'mad/view'
).then(function () {

	/*
	 * @class passbolt.view.component.ResourceDetails
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('passbolt.view.component.ResourceDetails', /** @static */ {

	}, /** @prototype */ {

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
		}

	});
});