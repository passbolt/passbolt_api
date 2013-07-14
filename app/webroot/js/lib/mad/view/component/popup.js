steal(
	'mad/view'
).then(function () {

	/*
	 * @class mad.view.component.Popup
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('mad.view.component.Popup', /** @static */ {

		'defaults': {}

	}, /** @prototype */ {
		
		/**
		 * Override parent function, to position the component 
		 * @return {void}
		 */
		'insertInDom': function (html) {
			var returnValue = this._super(html);
			// position the popup on the center of the screen
			$('.js_popup_box', this.element).position({
				of: this.element,
				my: "center center",
				at: "center center"
				// collision: "none none"
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
		}

	});
});