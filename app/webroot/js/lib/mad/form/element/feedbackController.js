steal(
	MAD_ROOT + '/form/formElement.js'
//	MAD_ROOT + '/view/template/component/feedback.ejs'
).then(function ($) {

	/*
	 * @class mad.form.element.FeedbackController
	 * @inherits mad.form.FormElement
	 * @parent mad.form
	 * 
	 * The Feedback Form Element Controller
	 * 
	 * @constructor
	 * Creates a new Feedback Form Element Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.form.element.FeedbackController}
	 */
	mad.form.FormElement.extend('mad.form.element.FeedbackController', /** @static */ {

		'defaults': {
			'label': 'Feedback Form Component',
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
		 * @return {mad.form.element.FeedbackController}
		 */
		'setValue': function (value) {
			this.value = value;
			return this;
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ****	********************************************************** */

		/* ************************************************************** */
		/* LISTEN TO THE STATE CHANGES */
		/* ************************************************************** */

		/**
		 * Listen to the change relative to the state Success
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateSuccess': function (go) {
			if (go) {
				this.element.html(this.value);
			}
		},

		/**
		 * Listen to the change relative to the state Error
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateError': function (go) {
			if (go) {
				this.element.html(this.value);
			}
		}

	});

});