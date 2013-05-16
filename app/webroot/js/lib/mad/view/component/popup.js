steal(
	'mad/view'
).then(function () {

	/*
	 * @class mad.view.component.Popup
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('mad.view.component.Popup', /** @static */ {

	}, /** @prototype */ {

		/**
		 * Render the popup
		 * @see {mad.view.View}
		 */
		'render': function (options) {
			var returnValue = this._super(options);
			// position the popup on the center of the screen
			this.element.find('.js_popup_box').position({
				my: "center center",
				at: "center center",
				of: this.element
			});
			return returnValue;
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
		'.js_popup_close click': function (el, ev) {
			this.element.remove();
		},
		
		/**
		 * listen when the user click on the escape key
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		' keypress': function (el, ev) {
			// console.log(ev.which);
		}

	});
});