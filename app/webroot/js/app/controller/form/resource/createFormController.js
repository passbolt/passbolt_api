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
		'defaults': {
			'templateBased': true
		}
	}, /** @prototype */ {

		/**
		 * After start hook.
		 * Create the form elements
		 * 
		 * @return {void}
		 */
		'afterStart': function () {
			// temporary for update demonstration
			this.options.data.Resource = this.options.data.Resource || {};

			// Add category id hidden field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_category_id'), {
					modelReference: 'passbolt.model.Resource.Category.id'
				}).start()
			);
			// Add resource name field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_name'), {
					modelReference: 'passbolt.model.Resource.name'
				}).start(),
				new mad.form.FeedbackController($('#js_field_name_feedback'), {}).start()
			);
			// Add resource uri field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_uri'), {
					modelReference: 'passbolt.model.Resource.uri'
				}).start(),
				new mad.form.FeedbackController($('#js_field_uri_feedback'), {}).start()
			);
			// Add resource username field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_username'), {
					modelReference: 'passbolt.model.Resource.username'
				}).start(),
				new mad.form.FeedbackController($('#js_field_username_feedback'), {}).start()
			);
			// Add secret id field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_secret_id'), {
					modelReference: 'passbolt.model.Resource.Secret.id'
				}).start()
				// new mad.form.FeedbackController($('#js_field_secret_feedback'), {}).start()
			);
			// Add secret data field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_secret'), {
					modelReference: 'passbolt.model.Resource.Secret.data'
				}).start()
				// new mad.form.FeedbackController($('#js_field_secret_feedback'), {}).start()
			);
			// Add resource description field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_description'), {
					modelReference: 'passbolt.model.Resource.description'
				}).start(),
				new mad.form.FeedbackController($('#js_field_description_feedback'), {}).start()
			);
		}

	});
});