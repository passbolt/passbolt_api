steal(
	'mad/form/formController.js',
	'app/view/template/form/user/passwordForm.ejs'
).then(function () {

		/**
		 * @class passbolt.form.user.PasswordFormController
		 * @inherits {mad.form.FormController}
		 * @parent index
		 *
		 * @constructor
		 * Instanciate a User Password Form Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {passbolt.form.user.PasswordFormController}
		 */
		mad.form.FormController.extend('passbolt.form.user.PasswordFormController', /** @static */ {
			defaults: {
				templateBased: true,
				passwordField: null
			}
		}, /** @prototype */ {

			/**
			 * After start hook.
			 * Initialize the form elements.
			 * @see {mad.Component}
			 */
			afterStart: function () {

				// Add secret data field{
				this.options.passwordField = new mad.form.Textbox($('#js_field_password'), {
					modelReference: 'passbolt.model.User.password'
				}).start();
				this.addElement(this.options.passwordField);
				// Add secret data in clear field
				this.options.passwordClear = this.addElement(
					new mad.form.Textbox($('#js_field_password_clear'), {
						'state': 'hidden'
					}).start()
				);

				// Show/Hide the password
				this.options.showPwdButton = new mad.component.Button($('#js_show_pwd_button'))
					.start();

				// generate a password
				this.options.genPwdButton = new mad.component.Button($('#js_gen_pwd_button'))
					.start();

				// The secret strength component
				var secret = can.getObject('data.Secret.data', this.options);
				var secretStrength = passbolt.model.SecretStrength.getSecretStrength(secret);

				this.options.secretStrength = new passbolt.component.SecretStrength($('#js_user_pwd_strength'), {
					secretStrength: secretStrength
				}).start();

				// Rebind controller events
				this.on();
			},

			/**
			 * Update the secret entropy
			 * @param {string} pwd The password to use to mesure the entropy
			 */
			updateSecretEntropy: function(pwd) {
				var secretStrength = passbolt.model.SecretStrength.getSecretStrength(pwd);
				this.options.secretStrength.load(secretStrength);
			},

			/* ************************************************************** */
			/* LISTEN TO THE VIEW EVENTS */
			/* ************************************************************** */

			/**
			 * Observe when the user is changing the password
			 * @param {HTMLElement} el The element the event occurred on
			 * @param {HTMLEvent} ev The event which occurred
			 */
			'{passwordField} changed': function(el, ev) {
				if (this.options.passwordField) {
					this.updateSecretEntropy(this.options.passwordField.getValue());
				}
			},

			/**
			 * Observe when the user is changing the password through the unscrumbeld field
			 * @param {HTMLElement} el The element the event occurred on
			 * @param {HTMLEvent} ev The event which occurred
			 */
			'{passwordClear} changed': function(el, ev) {
				var value = this.getElement('js_field_password_clear').getValue();
				this.getElement('js_field_password').setValue(value);
				this.updateSecretEntropy(value);
			},

			/**
			 * Observe when the user wants to see the password unscrumbled
			 * @param {HTMLElement} el The element the event occurred on
			 * @param {HTMLEvent} ev The event which occurred
			 */
			'{showPwdButton} click': function(el, ev) {
				var password = this.getElement('js_field_password');
				var passwordClear = this.getElement('js_field_password_clear');

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
			},

			/**
			 * Observe when the user wants to generate a password
			 * @param {HTMLElement} el The element the event occurred on
			 * @param {HTMLEvent} ev The event which occurred
			 */
			'{genPwdButton} click': function(el, ev) {
				var value = passbolt.model.Secret.generate();
				this.getElement('js_field_password').setValue(value);
				this.getElement('js_field_password_clear').setValue(value);
				this.updateSecretEntropy(value);
			}

		});
	});