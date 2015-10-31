steal(
	'mad/view/form/formElementView.js'
).then(function () {

	/*
	 * @class mad.view.form.element.DateView
	 * @inherits mad.view.form.FormElement
	 * @hide
	 * 
	 * @constructor
	 * 
	 * @return {mad.view.form.element.DateView}
	 */
	mad.view.form.FormElementView.extend('mad.view.form.element.DateView', /** @static */ {

	}, /** @prototype */ {

		/**
		 * Set the value of the date form element
		 * @param {mixed} value The value to set
		 * @return {void}
		 */
		'setValue': function (value) {
			this.val(value);
		}

	});
});