steal(
	'mad/form/formController.js'
	//'app/view/template/form/resource/createForm.ejs'
).then(function () {

	/**
	 * @class passbolt.controller.form.secret.CreateFormController
	 * @inherits {mad.form.FormController}
	 * @parent index
	 * 
	 * @constructor
	 * Instanciate a Secret Create Form Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.form.secret.CreateFormController}
	 */
	mad.form.FormController.extend('passbolt.controller.form.secret.CreateFormController', /** @static */ {
		'defaults': {
			'templateBased': false,
			// @todo should be dynamic functions of creation or update
			'action': 'create',
			'secret_i': null
		}
	}, /** @prototype */ {

		/**
		 * After start hook.
		 * Create the form elements
		 * 
		 * @return {void}
		 */
		'afterStart': function () {
			var self = this;

			// Add secret id hidden field.
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_secret_id_' + this.options.secret_i), {
					modelReference: 'passbolt.model.Secret.id',
					validate: false
				}).start()
			);

			// Add secret user id hidden field.
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_secret_user_id_' + this.options.secret_i), {
					modelReference: 'passbolt.model.Secret.user_id',
					validate: false
				}).start()
			);

			// Add secret data hidden field.
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_secret_data_' + this.options.secret_i), {
					modelReference: 'passbolt.model.Secret.data',
					validate: false
				}).start()
			);

			// Rebind controller events
			this.on();
		}

	});
});