steal(
	'mad/view/form/formElementView.js'
).then(function () {

	/*
	 * @class mad.view.form.element.TextboxView
	 * @inherits mad.view.form.FormElement
	 * @hide
	 * 
	 * @constructor
	 * Creates a new Textbox Form Element View
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller. These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.view.form.element.TextboxView}
	 */
	mad.view.form.FormElementView.extend('mad.view.form.element.TextboxView', /** @static */ {

		'defaults': { }

	}, /** @prototype */ {

		// Constructor like
		'init': function(el, opts) {
			this._super(el, opts);
			this._keypressTimeout = null;
			this.keypressTimeout = this.controller.options.keypressTimeout;
		},

		/**
		 * Get the value of the textbox form element
		 * @return {mixed} The value of the component
		 */
		'getValue': function (value) {
			return this.element.val();
		},

		/**
		 * Set the value of the textbox form element
		 * @param {mixed} value The value to set
		 * @return {void}
		 */
		'setValue': function (value) {
			this.element.val(value);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Listen to the view event change
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		' keypress': function (el, ev) {
			var self = this;

			if(this._keypressTimeout != null) {
				clearTimeout(this._keypressTimeout);
			}

			if(this.getValue().length >= 2) {
				this._keypressTimeout = setTimeout(function() {
					el.trigger('changed', {
						value: self.getValue()
					});
				}, this.keypressTimeout);
			}
		},

		/**
		 * Listen to the view event change
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		' keydown': function (el, ev) {
			var self = this;

			// catch backspace
			if(ev.which == 8) {
				if(this._keypressTimeout != null) {
					clearTimeout(this._keypressTimeout);
				}
				
				this._keypressTimeout = setTimeout(function() {
					el.trigger('changed', {
						value: self.getValue()
					});
				}, this.keypressTimeout);
			}
		}
	});
});