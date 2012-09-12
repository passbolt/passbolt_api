steal(
	MAD_ROOT + '/form/formElement.js',
	MAD_ROOT + '/view/template/component/input.ejs'
).then(function ($) {

	/*
	 * @class mad.form.element.InputController
	 * @inherits mad.form.FormElement
	 * @parent mad.form
	 * 
	 * The Input Form Element Controller is our implementation of the UI component input
	 * 
	 * @constructor
	 * Creates a new Input Form Element Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.form.element.InputController}
	 */
	mad.form.FormElement.extend('mad.form.element.InputController', /** @static */ {

		'defaults': {
			'label': 'Input Form Component',
			'templateBased': false,
			'templateUri': '//' + MAD_ROOT + '/view/template/component/input.ejs',
			'value': null,
			'event': {
				'click': null
			}
		}

	}, /** @prototype */ {

		/**
		 * Value of the button. This value will be released when events occured
		 * @type {string}
		 */
		'value': null,

		// Construcor
		'init': function (el, options) {
			this._super();
			if (this.options.value) {
				this.setValue(this.options.value);
			}
		},

		/**
		 * Get the value of the input element
		 * @return {mixed} value The value of the input element
		 */
		'getValue': function () {
			this.value = this.element.val();
			return this.value;
		},

		/**
		 * Set the value of the button
		 * @param {mixed} value The value to set
		 * @return {void}
		 */
		'setValue': function (value) {
			this.value = value;
			this.element.val(this.value);
		}


		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

	});

});