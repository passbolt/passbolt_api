steal(
	MAD_ROOT + '/form/formController.js',
	'app/view/template/form/resource/createForm.ejs'
).then(function ($) {

	/**
	 * @class passbolt.controller.form.resource.CreateFormController
	 * @inherits {mad.form.FormController}
	 * @parent index
	 * 
	 * @constructor
	 * Instanciate a Resource Create Form Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.form.resource.CreateFormController}
	 */
	mad.form.FormController.extend('passbolt.controller.form.resource.CreateFormController', /** @static */ {

	}, /** @prototype */ {

		/**
		 * Init the form elements
		 * @todo Think about a common form controller function initFormElement
		 * @return {void}
		 */
		'initFormElement': function () {
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_category_id'), {
					'value': this.options.data.categoryId
				})
			);
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_name'), {}),
				new mad.form.FeedbackController($('#js_field_name_feedback'), {})
			);
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_username'), {}),
				new mad.form.FeedbackController($('#js_field_username_feedback'), {})
			);
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_uri'), {}),
				new mad.form.FeedbackController($('#js_field_uri_feedback'), {})
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
		}

	});
});