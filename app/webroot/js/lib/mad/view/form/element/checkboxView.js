steal(
	'mad/view/form/formElementView.js'
).then(function () {

	/*
	 * @class mad.view.form.element.CheckboxView
	 * @inherits mad.view.form.FormElement
	 * @hide
	 * 
	 * @constructor
	 * Creates a new Checkbox Form Element View
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller. These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.view.form.element.CheckboxView}
	 */
	mad.view.form.FormElementView.extend('mad.view.form.element.CheckboxView', /** @static */ {

	}, /** @prototype */ {

		/**
		 * Get the value of the checkbox form element
		 * @return {array}
		 */
		'getValue': function () {
			var returnValue = [];
			this.element.find('input:checked').each(function () {
				returnValue.push($(this).val());
			});
			return returnValue;
		},

		/**
		 * Set the value of the checkbox form element
		 * @param {array} value An array containing the value to check
		 * @return {void}
		 */
		'setValue': function (value) {
			value = typeof value != 'undefined' && value != null ? value : [];
			this.element.find('input').each(function () {
				// if the value of the input is found in the array of value given, check the box
				if (value.indexOf($(this).val()) != -1) {
					$(this).attr('checked', 'checked');
				} else {
					$(this).removeAttr('checked');
				}
			});
			// this.element.find('input').val(value);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Listen to the view event click
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'input click': function (el, ev) {
			ev.stopPropagation();
			// ev.preventDefault();
			
			if (el.attr('checked')) {
				this.element.trigger('checked', el.val());
			} else {
				this.element.trigger('unchecked', el.val());
			}
		},

		/**
		 * Listen to the view event change
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'input change': function (el, ev) {
			ev.stopPropagation();
			// ev.preventDefault();
			
			this.element.trigger('changed', {value: this.getValue()});
		}

	});
});