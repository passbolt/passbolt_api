steal(
	MAD_ROOT + '/controller/componentController.js',
	MAD_ROOT + '/view/form/formElementView.js'
).then(function ($) {

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
			'value': null,
			'callbacks': {
				'changed': function (el, ev, value) {}
			}
		},

		'listensTo': [
			'changed'
		]

	},/** @prototype */ {

		/**
		 * The value of the Form Element
		 * @type {mixed}
		 */
		'value': null,

		// constructor like
		'init': function (el, options) {
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
			} else {
				// Check from the associated HTML element name attribute
				var nameAttribute = this.view.getName();
				if (nameAttribute != null) {
					returnValue = nameAttribute;
				}
			}
			return returnValue;
		},

		/**
		 * Get the form element associated model
		 * @return {mad.model.Model}
		 */
		'getModel': function () {
			var returnValue = null;
			var split = this.getModelReference().split('.');
			var modelName = split.slice(0, split.length - 1).join('.');
			returnValue = $.String.getObject(modelName);
			return returnValue;
		},

		/**
		 * Get the form element attribute name
		 * @return {string}
		 */
		'getModelAttributeName': function () {
			var returnValue = null;
			var split = this.getModelReference().split('.');
			returnValue = split[split.length - 1];
			return returnValue;
		},

		/**
		 * Get the name of the form element
		 * @return {string}
		 */
		'getName': function () {
			return this.view.getName();
		},

		/**
		 * Get the value of the form element
		 * @return {mixed} value The value of the form element
		 */
		'getValue': function () {
			return this.value;
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

		/* ************************************************************** */
		/* LISTEN TO THE STATE CHANGES */
		/* ************************************************************** */

		/**
		 * Listen to the change relative to the state Ready
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateReady': function (go) {
			if (go) {
				// set the value of the form element
				if (this.options.value) {
					this.setValue(this.options.value);
				}
			}
		},

		/**
		 * Listen to the change relative to the state Error
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateError': function (go) {
			// override the function to catch the state switch to error
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Listen to the view event changed
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mixed} value The selected value
		 * @return {void}
		 */
		'changed': function (el, event, value) {
			this.value = value;
			if (this.options.callbacks.changed) {
				this.options.callbacks.changed(this.value);
			}
		}

	});
});