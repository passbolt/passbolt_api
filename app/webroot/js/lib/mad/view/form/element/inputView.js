steal(
	MAD_ROOT + '/view/form/formElementView.js'
).then(function ($) {

	/*
	 * @class mad.view.form.element.InputView
	 * @inherits mad.view.form.FormElement
	 * @hide
	 * 
	 * @constructor
	 * Creates a new Input Form Element View
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller. These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.view.form.element.InputView}
	 */
	mad.view.form.FormElementView.extend('mad.view.form.element.InputView', /** @static */ {

	}, /** @prototype */ {

		/**
		 * Set the value of the input form element
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
		'change': function (el, event) {
			el.trigger('changed', el.val());
		}

	});
});