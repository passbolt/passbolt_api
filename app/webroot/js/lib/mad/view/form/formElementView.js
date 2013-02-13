steal(
	'mad/view'
).then(function () {

	/*
	 * @class mad.view.form.FormElementView
	 * @inherits mad.view.View
	 * @hide
	 * 
	 * @constructor
	 * 
	 * @return {mad.view.form.FormElementView}
	 */
	mad.view.View.extend('mad.view.form.FormElementView', /** @static */ {

	}, /** @prototype */ {

		/**
		 * Get the name of the form element
		 * @return {string}
		 */
		'getName': function () {
			return this.element.attr('name');
		},

		/**
		 * Set the value of the form element
		 * @param {mixed} value The value to set
		 * @return {void}
		 */
		'setValue': function (value) {
			steal.dev.warn('The setValue function has not been implemented for the class ' + this.getClass().fullName);
		},
		
		/**
		 * Reset the form element view
		 */
		'reset': function () {
			steal.dev.warn('The reset function has not been implemented for the class ' + this.getClass().fullName);
		}
	});
});