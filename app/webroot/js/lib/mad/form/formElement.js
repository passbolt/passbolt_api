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
			// the model representing this element
			'modelReference': null,
			'value': null,
			'callbacks': {
				'changed': function (el, ev, value) {}
			}
		}

	},/** @prototype */ {

		/**
		 * The value of the Form Element
		 * @type {mixed}
		 */
		'value': null,

		// Constructor like
		'init': function (el, options) {
			this._super(el, options);
			// if a default value has been given
			// do it after the component has been rendered
			if(options.value != null) {
				this.setValue(options.value);
			}
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
		 * Get the value of the form element
		 * @return {mixed} value The value of the form element
		 */
		'getValue': function () {
			return this.value;
		},

		/**
		 * Render the component
		 * @see {mad.view.View}
		 * @param {array} options Associative array of options
		 * @param {boolean} options.display Display the rendered component. If true
		 * the rendered component will be push in the DOM else the rendered component
		 * will be stored in the instance's variable renderedView
		 * @return {mixed} Return true if the method does not encountered troubles else
		 * return false. If the option display is set to false, return the rendered view
		 */
		'render': function (options) {
			this.setValue(this.options.value);
			this._super();
		},

		/**
		 * Switch the component to its initial state
		 * @return {void}
		 */
		'reset': function () {
			console.log('is reseting ' + this.getClass().fullName);
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