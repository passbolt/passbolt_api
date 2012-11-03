steal(
	'mad/form/formElement.js',
	'mad/view/form/element/radiobuttonView.js',
	'mad/view/template/component/input.ejs'
).then(function () {

	/*
	 * @class mad.form.element.RadiobuttonController
	 * @inherits mad.form.FormElement
	 * @parent mad.form.element
	 * 
	 * The Radiobutton Form Element Controller is our implementation of the UI component input
	 * 
	 * @constructor
	 * Creates a new Radiobutton Form Element Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.form.element.RadiobuttonController}
	 */
	mad.form.FormChoiceElement.extend('mad.form.element.RadiobuttonController', /** @static */ {

		'defaults': {
			'label': 'Radiobutton Form Element Controller',
			'templateUri': '//' + 'mad/view/template/component/input.ejs',
			'viewClass': mad.view.form.element.RadiobuttonView
		}

	}, /** @prototype */ { });

});