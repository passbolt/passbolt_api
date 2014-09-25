steal(
	'mad/controller/componentController.js',
	'mad/view/template/component/button.ejs'
).then(function () {

	/*
	 * @class mad.controller.component.ButtonController
	 * @inherits mad.controller.ComponentController
	 * @parent mad.controller.component
	 * 
	 * The Button class Controller is our implementation of the UI component button.
	 * 
	 * ## Example
	 * @demo lib/mad/demo/controller/component/simple_button.html
	 * 
	 * @constructor
	 * Creates a new Button Controller Component
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.controller.component.ButtonController}
	 */
	mad.controller.ComponentController.extend('mad.controller.component.ButtonController', /** @static */ {

		'defaults': {
			'label': 'Button Component',
			'templateUri': 'mad/view/template/component/button.ejs',
			'templateBased': false,
			'value': null,
			'events': {
				'click': null
			},
			'tag': 'button'
		}

	}, /** @prototype */ {

		/**
		 * Value of the button.
		 * This value will be released as event parameter when an event occured
		 * @type {string}
		 */
		'value': null,

		// Construcor like
		'init': function (el, options) {
			this._super(el, options);
			this.value = options.value;
		},

		/**
		 * Get the value of the button
		 * @return {mixed} value The value of the button
		 */
		'getValue': function () {
			return this.value;
		},

		/**
		 * Set the value of the button
		 * @param {mixed} value The value to set
		 * @return {mad.controller.component.ButtonController}
		 */
		'setValue': function (value) {
			this.value = value;
			return this;
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Listen to the event click on the DOM button element
		 * @return {void}
		 */
		'click': function (el, ev) {
			// if the component is disabled, stop the propagation
			if (this.state.is('disabled')) {
				ev.stopImmediatePropagation();
			} else {
				// if callbacks associated to the button
				if (this.options.events.click) {
					this.options.events.click(this.element, ev, this.value);
				}
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
		'stateDisabled': function (go) {
			if (go) {
				this.element.attr('disabled', 'disabled')
					.addClass('disabled');
			} else {
				this.element.removeAttr('disabled')
					.removeClass('disabled');
			}
		}
	});

});