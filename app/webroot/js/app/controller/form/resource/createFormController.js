steal(
	'mad/form/formController.js',
	'app/view/template/form/resource/createForm.ejs'
).then(function () {

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
			// temporary for update demonstration
			this.options.data.Resource = this.options.data.Resource || {};

			// Add category id hidden field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_category_id'), {
					modelReference: 'passbolt.model.Resource.Category.id'
				})
			);
			// Add resource name field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_name'), {
					modelReference: 'passbolt.model.Resource.name'
				}),
				new mad.form.FeedbackController($('#js_field_name_feedback'), {})
			);
			// Add resource username field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_username'), {
					modelReference: 'passbolt.model.Resource.username'
				}),
				new mad.form.FeedbackController($('#js_field_username_feedback'), {})
			);
			// Add resource uri field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_uri'), {
					modelReference: 'passbolt.model.Resource.uri'
				}),
				new mad.form.FeedbackController($('#js_field_uri_feedback'), {})
			);
			// Add resource description field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_description'), {
					modelReference: 'passbolt.model.Resource.description'
				}),
				new mad.form.FeedbackController($('#js_field_description_feedback'), {})
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