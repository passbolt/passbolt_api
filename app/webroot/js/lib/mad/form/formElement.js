/*
 * @page mad.form Form
 * @tag mad.form
 * @parent index
 * 
 * 
 */

steal(
	MAD_ROOT + '/controller/componentController.js'
).then(function ($) {

	/*
	 * @class mad.form.FormElement
	 * @inherits mad.controller.ComponentController
	 * @parent mad.form
	 * 
	 * The representation a form element
	 * 
	 * @constructor
	 * Creates a new Form Element
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.controller.FormController}
	 */
	mad.controller.ComponentController.extend('mad.form.FormElement', {

		// constructor like
		'init': function (el, options) {
			this._super(el, options);
		},

		'validate': function () {
			
		},

		'getName': function () {
			return this.element.attr('name');
		},

		'getValue': function () {
			
		},

		'setValue': function (value) {
			
		},

		'stateError': function (go) {
			
		}

	});
});