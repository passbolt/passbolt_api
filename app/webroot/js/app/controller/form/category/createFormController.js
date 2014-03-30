steal(
	'mad/form/formController.js',
	'app/view/template/form/category/createForm.ejs'
).then(function () {

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
	 * @return {passbolt.controller.form.category.CreateFormController}
	 */
	mad.form.FormController.extend('passbolt.controller.form.category.CreateFormController', /** @static */ {
		'defaults': {
			'templateBased': true,
			'action': 'create'
		}
	}, /** @prototype */ {

		/**
		 * After start hook.
		 * Create the form elements
		 * 
		 * @return {void}
		 */
		'afterStart': function () {
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_parent_id'), {
					modelReference: 'passbolt.model.Category.parent_id'
				}).start()
			);
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_name'), {
					modelReference: 'passbolt.model.Category.name'
				}).start(),
				new mad.form.FeedbackController($('#js_field_name_feedback')).start()
			);
		}

	});
});