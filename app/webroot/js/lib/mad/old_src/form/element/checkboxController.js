steal(
	'mad/form/formElement.js',
	'mad/view/form/element/checkboxView.js',
	'mad/view/template/form/element/checkbox.ejs'
).then(function () {

	/*
	 * @class mad.form.element.CheckboxController
	 * @inherits mad.form.FormElement
	 * @parent mad.form.element
	 * 
	 * The Checkbox Form Element Controller is our implementation of the UI component input
	 * 
	 * @constructor
	 * Creates a new Input Form Element Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.form.element.CheckboxController}
	 */
	mad.form.FormChoiceElement.extend('mad.form.element.CheckboxController', /** @static */ {

		'defaults': {
			'label': 'CheckboxController Form Element Controller',
			'templateBased': true,
			'templateUri': 'mad/view/template/form/element/checkbox.ejs',
			'viewClass': mad.view.form.element.CheckboxView
		}

	}, /** @prototype */ { });

});
