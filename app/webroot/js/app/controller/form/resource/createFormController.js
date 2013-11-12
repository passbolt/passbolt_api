steal(
	'mad/form/formController.js',
	'app/controller/component/secretStrengthController.js',
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
			'templateBased': true,
			'secretField': null
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
			);
			// Add secret data field
			this.options.secretField = new mad.form.element.TextboxController($('#js_field_secret'), {
					modelReference: 'passbolt.model.Resource.Secret.data'
				}).start();
			this.addElement(this.options.secretField);
			// Add secret data in clear field
			this.options.passwordClear = this.addElement(
				new mad.form.element.TextboxController($('#js_field_secret_clear'), {
					// modelReference: 'passbolt.model.Resource.Secret.data'
					'state': 'hidden'
				}).start()
			);
			// Add resource description field
			this.addElement(
				new mad.form.element.TextboxController($('#js_field_description'), {
					modelReference: 'passbolt.model.Resource.description'
				}).start(),
				new mad.form.FeedbackController($('#js_field_description_feedback'), {}).start()
			);
			
			// Show/Hide the password
			this.options.showPwdButton = new mad.controller.component.ButtonController($('#js_show_password_button'))
				.start();

			// The secret strenght compone nt
			var secretStrength = null;
			if(this.options.data && this.options.data.Secret && this.options.data.Secret.data) {
				secretStrength = passbolt.model.SecretStrength.getSecretStrength(this.options.data.Secret.data);
			}
			this.options.secretStrength = new passbolt.controller.component.SecretStrengthController($('#js_rs_pwd_strength'), {
				secretStrength: secretStrength
			})
				.start();

			// Rebind controller events
			this.on();
		},

		/**
		 * Update the secret entropy
		 * @param {string} pwd The password to use to mesure the entropy
		 * @return {void}
		 */
		'updateSecretEntropy': function(pwd) {
			var secretStrength = passbolt.model.SecretStrength.getSecretStrength(pwd);
			this.options.secretStrength.load(secretStrength);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the user is changing the password
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{secretField} changed': function(el, ev) {
			this.updateSecretEntropy(this.options.secretField.getValue());
		},

		/**
		 * Observe when the user is changing the password through the unscrumbeld field
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{passwordClear} change': function(el, ev) {
			var value = this.getElement('js_field_secret_clear').getValue();
			this.getElement('js_field_secret')
				.setValue(value);
		},

		/**
		 * Observe when the user wants to see the password unscrumbled
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Category} category The selected category
		 * @return {void}
		 */
		'{showPwdButton} click': function(el, ev) {
			var password = this.getElement('js_field_secret');
			var passwordClear = this.getElement('js_field_secret_clear');
			
			// if the password is already hidden
			if (password.state.is('hidden')) {
				// hide the unscrambled password
				passwordClear.setState('hidden');
				// show the password field
				password.setState('ready');
				// unpush the button
				this.options.showPwdButton.view.removeClass('selected');
			}
			else {
				// hide the password field
				password.setState('hidden');
				// display the unscrambled password
				passwordClear.setState('ready');
				passwordClear.setValue(password.getValue());
				// push the button
				this.options.showPwdButton.view.addClass('selected');
			}
		}

	});
});