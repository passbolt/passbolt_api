steal(
	'mad/view'
).then(function () {

	/*
	 * @class mad.view.component.Dialog
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('mad.view.component.Dialog', /** @static */ {

		'defaults': {}

	}, /** @prototype */ {

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Listen to the user interaction click with the close button
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'.dialog-close click': function (el, ev) {
			this.element.remove();
		}

	});
});