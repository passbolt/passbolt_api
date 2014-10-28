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

		/**
		 * Set the title
		 * @param {string} title The new title
		 */
		'setTitle': function (title) {
			$('.dialog-header h2', this.element).html(title);
		},

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
		},

		/**
		 * Listen to the user interaction keyboard press
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{window} keyup': function (el, ev) {
			if (ev.keyCode == 27) {
				this.element.remove();
			}
		},

		/**
		 * Listen to click on the cancel link
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		' .js-dialog-cancel click': function (el, ev) {
			this.element.remove();
		}
	});
});