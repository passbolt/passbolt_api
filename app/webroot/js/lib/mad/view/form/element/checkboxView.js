steal(
	MAD_ROOT + '/view/form/formElementView.js'
).then(function ($) {

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

		'setAvailableValues': function (availableValues) {
			for (var value in availableValues) {
				var html = '<input type="checkbox" name="' + this.getName() + '" value="' + value + '" />' + availableValues[value];
				this.element.append(html);
			}
		},

		/**
		 * Get the value of the checkbox form element
		 * @return {array}
		 */
		'getValue': function () {
			var returnValue = [];
			this.element.find(':checked').each(function(){
				returnValue.push($(this).val());
			});
			return returnValue;
		},

		/**
		 * Set the value of the checkbox form element
		 * @param {mixed} value The value to set
		 * @return {void}
		 */
		'setValue': function (value) {
			this.element.find('input').val(value);
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
			el.trigger('changed', {
				value: this.getValue()
			});
		}

	});
});