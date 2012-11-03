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

	}, /** @prototype */ {

		/**
		 * Init the form elements
		 * @todo Think about a common form controller function initFormElement
		 * @return {void}
		 */
		'initFormElement': function () {
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_parent_id'), {
					modelReference: 'passbolt.model.Category.parent_id'
				})
			);
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_name'), {
					modelReference: 'passbolt.model.Category.name'
				}),
				new mad.form.FeedbackController($('#js_field_name_feedback'))
			);
		},

		/**
		 * Render the form and init the form elements
		 * @return {void}
		 * @todo Think about a common form controller function initFormElement
		 */
		'render': function () {
			this._super();
			this.initFormElement();
			this.load(this.options.data);
		}

	});
});