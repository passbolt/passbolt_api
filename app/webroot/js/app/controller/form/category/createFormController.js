steal(
	MAD_ROOT + '/form/formController.js',
	'app/view/template/form/category/createForm.ejs'
).then(function ($) {

	/**
	 * @class passbolt.controller.form.category.CreateFormController
	 * @inherits {mad.form.FormController}
	 * @parent index
	 * 
	 * @constructor
	 * Instanciate a Category Create Form Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.form.category.CreateFormController}
	 */
	mad.form.FormController.extend('passbolt.controller.form.category.CreateFormController', /** @static */ {

	}, /** @prototype */ {

		'initFormElement': function () {
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_parent_id'), {
					'value': this.options.data.parentId
				})
			);
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_name'), {}),
				new mad.form.FeedbackController($('#js_field_name_feedback'), {})
			);
		},

		'render': function () {
			this._super();
			this.initFormElement();
		}

	});
});