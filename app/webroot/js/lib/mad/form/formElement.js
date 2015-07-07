
steal(
	'mad/controller/componentController.js',
	'mad/view/form/formElementView.js'
).then(function () {

	/*
	 * @class mad.form.FormElement
	 * @inherits mad.controller.ComponentController
	 * @parent mad.form
	 * 
	 * Our Form Element class which will be the parent of any Form Elements
	 * 
	 * @constructor
	 * Creates a new Form Element
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.controller.FormElement}
	 */
	mad.controller.ComponentController.extend('mad.form.FormElement', /** @static */ {

		'defaults': {
			'viewClass': mad.view.form.FormElementView,
			'templateBased': false,
			// The model representing this element.
			'modelReference': null,
			// The element should be validated.
			'validate': true,
			// The element should be validated following the given function. Priority max.
			'validateFunction': null,
			// The default value of the component.
			'defaultValue': null,
			'value': null,
			'callbacks': {
				'changed': function (el, ev, value) {}
			}
		}

	},/** @prototype */ {

		// constructor like
		'init': function(el, options) {
			/**
			 * The value of the Form Element
			 * @type {mixed}
			 */
			this.defaultValue = options.value;
			this.value = options.value;
			this._super(el, options);
		},

		/**
		 * Get the associated model.attribute
		 * @return {string}
		 */
		'getModelReference': function () {
			var returnValue = null;
			if (this.options.modelReference) {
				returnValue = this.options.modelReference;
			}
			return returnValue;
		},

		/**
		 * Check if the element has to be validated.
		 * @return {bool}
		 */
		'requireValidation': function () {
			var returnValue = false;
			if (this.options.validate) {
				returnValue = this.options.validate;
			}
			return returnValue;
		},

		/**
		 * Set the associated model.attribute
		 * @param {string} modelReference
		 */
		'setModelReference': function (modelReference) {
			this.options.modelReference = modelReference;
		},

		/**
		 * Get the value of the form element
		 * @return {mixed} value The value of the form element
		 */
		'getValue': function () {
			return this.value;
		},

		/**
		 * Switch the component to its initial state
		 * @return {void}
		 */
		'reset': function () {
			this.setState('reset');
			this.setValue(this.options.value);
			this.setState('ready');
		},

		/**
		 * Set the value of the form element
		 * @param {mixed} value The value to set
		 * @return {mad.form.FormElement}
		 */
		'setValue': function (value) {
			this.value = value;
			this.view.setValue(this.value);
			return this;
		},

		/**
		 * After start hook
		 */
		'afterStart': function() {
			// set the value after the component 
			this.setValue(this.options.value);
		},

		/* ************************************************************** */
		/* LISTEN TO THE STATE CHANGES */
		/* ************************************************************** */

		/**
		 * Listen to the change relative to the state Reset
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateReset': function (go) {
			this.setState('ready');
		},

		/**
		 * Listen to the change relative to the state Ready
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateReady': function (go) {
			// override the function to catch the state switch to ready
		},

		/**
		 * Listen to the change relative to the state Error
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateError': function (go) {
			// override the function to catch the state switch to error
		},

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
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Listen to the view event changed
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mixed} data The event data
		 * @return {void}
		 */
		' changed': function (el, event, data) {
			this.value = data.value;
			if (this.options.callbacks.changed) {
				this.options.callbacks.changed(this.value);
			}
		}

	});
});