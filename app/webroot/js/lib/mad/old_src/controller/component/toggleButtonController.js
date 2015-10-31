steal(
	'mad/controller/component/buttonController.js',
	'mad/view/template/component/button.ejs'
).then(function () {

	/*
	 * @class mad.controller.component.ToggleButtonController
	 * @inherits mad.controller.component.ButtonController
	 * @parent mad.controller.component
	 * 
	 * The Toggle Button class Controller is our implementation of the UI component toggle button.
	 * 
	 * ## Example
	 * @demo lib/mad/demo/controller/component/simple_button.html
	 * 
	 * @constructor
	 * Creates a new Toggle Button Controller Component
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.controller.component.ToggleButtonController}
	 */
	mad.controller.component.ButtonController.extend('mad.controller.component.ToggleButtonController', /** @static */ {

		'defaults': {
		}

	}, /** @prototype */ {

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Listen to the event click on the DOM toggle button element
		 * @return {void}
		 */
		'click': function (el, ev) {
			this._super(el, ev);
			if(!this.state.is('selected')) {
				this.setState('selected');
			} else {
				this.setState('ready');
			}
		},

		/* ************************************************************** */
		/* LISTEN TO THE STATE CHANGES */
		/* ************************************************************** */

		/**
		 * Listen to the change relative to the state Disabled
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateSelected': function (go) {
			if (go) {
				this.element.addClass('selected');
			} else {
				this.element.removeClass('selected');
			}
		}
	});

});