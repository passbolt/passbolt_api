steal(
	MAD_ROOT + '/form/formElement.js',
	MAD_ROOT + '/view/form/element/dateView.js',
	MAD_ROOT + '/view/template/component/input.ejs'
).then(function ($) {

	/*
	 * @class mad.form.element.DateController
	 * @inherits mad.form.FormElement
	 * @parent mad.form
	 * 
	 * The Date Form Element Controller is our implementation of the UI component date
	 * 
	 * @constructor
	 * Creates a new Date Form Element Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.form.element.DateController}
	 */
	mad.form.FormElement.extend('mad.form.element.DateController', /** @static */ {

		'defaults': {
			'label': 'Input Date Element Controller',
			'templateBased': false,
			'templateUri': '//' + MAD_ROOT + '/view/template/component/input.ejs',
			'value': null,
			'event': {
				'click': null
			}
		}

	}, /** @prototype */ {

	});

});