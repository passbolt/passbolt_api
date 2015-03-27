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

		'defaults': {
            'blackKeys': [13]
        }

	}, /** @prototype */ {

		// Constructor like
		'init': function(el, opts) {
			this._super(el, opts);
			this._changeTimeout = null;
			this.changeTimeout = this.getController().options.changeTimeout;
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
		 * @todo take a look to the input event
		 * @todo https://developer.mozilla.org/en-US/docs/Web/Events/input
		 * Listen to the view event change
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		' keypress': function (el, ev) {
			var self = this;

            // Escape the following keys.
            if ($.inArray(ev.which, this.options.blackKeys) != -1) {
                return;
            }

			if(this._changeTimeout != null) {
				clearTimeout(this._changeTimeout);
			}

			if(this.getValue().length >= 2) {
				this._changeTimeout = setTimeout(function() {
					el.trigger('changed', {
						value: self.getValue()
					});
				}, this.changeTimeout);
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
				if(this._changeTimeout != null) {
					clearTimeout(this._changeTimeout);
				}
				
				this._changeTimeout = setTimeout(function() {
					el.trigger('changed', {
						value: self.getValue()
					});
				}, this.changeTimeout);
			}
		},


		/**
		 * Listen to the view event change
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		' change': function (el, ev) {
			this.getController().value = this.element.val();
		},

		/**
		 * Listen to the view event paste
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		' paste': function (el, ev) {
			var self = this;
			setTimeout(function() {
	        el.trigger('changed', {
						value: self.getValue()
					});
	    }, 0); // note the 0 milliseconds
		},

		/**
		 * Listen to the view event cut
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		' cut': function (el, ev) {
			var self = this;
			setTimeout(function() {
	        el.trigger('changed', {
						value: self.getValue()
					});
	    }, 0); // note the 0 milliseconds
		}
	});
});