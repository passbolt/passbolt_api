import 'mad/form/form';
import 'mad/form/element/textbox';
import 'app/component/secret_strength';
import 'app/model/user';
import 'app/view/template/form/user/create.ejs!';

// Define namespace.
passbolt.form.user = passbolt.form.user ? passbolt.form.user : {};

/**
 * @class passbolt.form.user.CreateFormController
 * @inherits {mad.form.FormController}
 * @parent index
 *
 * @constructor
 * Instanciate a User Create Form Controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.form.user.CreateFormController}
 */
var CreateForm = passbolt.form.user.Create = mad.Form.extend('passbolt.form.user.Create', /** @static */ {

	defaults: {
		templateBased: true,
		passwordField: null,
		currentPasswordField: null,
		// @todo should be dynamic functions of creation or update
		action: 'create',
		templateUri: 'app/view/template/form/user/create.ejs'
	}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * Create the form elements.
	 */
	afterStart: function () {
		// temporary for update demonstration
		this.options.data.User = this.options.data.User || {};

		var activeField = this.addElement(
			new mad.form.Textbox($('#js_field_user_active'), {
				modelReference: 'passbolt.model.User.active'
			}).start()
		);

		// Add user first name field
		this.addElement(
			new mad.form.Textbox($('#js_field_first_name'), {
				modelReference: 'passbolt.model.User.Profile.first_name'
			}).start(),
			new mad.form.Feedback($('#js_field_first_name_feedback'), {}).start()
		);

		// Add user last name field
		this.addElement(
			new mad.form.Textbox($('#js_field_last_name'), {
				modelReference: 'passbolt.model.User.Profile.last_name'
			}).start(),
			new mad.form.Feedback($('#js_field_last_name_feedback'), {}).start()
		);

		// Role box.
		// Build roles data.
		var roles = {};
		console.log(mad.config);
		roles[cakephpConfig.roles.admin] = __('This user is an administrator');
		roles[cakephpConfig.roles.user] = __('This user is a normal user');
		// Role component.
		this.options.role = new mad.form.Checkbox($('#js_field_role_id'), {
				name: 'role_id',
				modelReference: 'passbolt.model.User.role_id',
				availableValues: roles
			}
		).start();

		// Add role element to form.
		this.addElement(
			this.options.role,
			new mad.form.Feedback($('#js_field_role_id_feedback'), {}).start()
		);
		// Hide everything that is not admin.
		$('input[type=checkbox]', $('#js_field_role_id')).not("[value='" + cakephpConfig.roles.admin + "']").hide().next('label').hide();

		// Add resource username field
		this.addElement(
			new mad.form.Textbox($('#js_field_username'), {
				modelReference: 'passbolt.model.User.username'
			}).start(),
			new mad.form.Feedback($('#js_field_username_feedback'), {}).start()
		);

		// Add secret data field
		// Only while creating a new user
		if (this.options.action != 'create') {
			this.options.passwordField = new mad.form.Textbox($('#js_field_password'), {
				modelReference: 'passbolt.model.User.password'
			}).start();
			this.addElement(this.options.passwordField);
			// Add secret data in clear field
			this.options.passwordClear = this.addElement(
				new mad.form.Textbox($('#js_field_password_clear'), {
					state: 'hidden'
				}).start()
			);

			// Declare current password field for non admin users.
			var userRole = passbolt.model.User.getCurrent().Role.name;
			var userId = this.options.data.id;
			// Current password is required only for non admin users.
			if (userRole != 'admin'  || (userRole == 'admin' && userId == passbolt.model.User.getCurrent().id)) {
				this.options.currentPasswordField = new mad.form.Textbox($('#js_field_current_password'), {
					modelReference: 'passbolt.model.User.current_password'
				}).start();
				this.addElement(
					this.options.currentPasswordField,
					new mad.form.Feedback($('#js_field_current_password_feedback'), {}).start()
				);
				// Button to see clear current password.
				this.options.currentPasswordClear = this.addElement(
					new mad.form.Textbox($('#js_field_current_password_clear'), {
						state: 'hidden'
					}).start()
				);
				// Show/Hide the password
				this.options.showCurrPwdButton = new mad.component.Button($('#js_show_curr_pwd_button'))
					.start();
			}

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
		}

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
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 */
	'{passwordField} changed': function(el, ev) {
		if (this.options.passwordField) {
			this.updateSecretEntropy(this.options.passwordField.getValue());
		}
	},

	/**
	 * Observe when the user is changing the password through the unscrumbeld field
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 */
	'{passwordClear} changed': function(el, ev) {
		var value = this.getElement('js_field_password_clear').getValue();
		this.getElement('js_field_password').setValue(value);
		this.updateSecretEntropy(value);
	},

	/**
	 * Observe when a role is checked. and Unselect the other one.
	 * @param el
	 * @param ev
	 * @param roleId
	 */
	'{role} checked': function(el, ev, roleId) {
		// Force only one value.
		this.options.role.setValue(roleId);
	},

	/**
	 * Observe when a role is changed. If no role is selected, select user as default.
	 * @param el
	 * @param ev
	 * @param val
	 */
	'{role} changed': function(el, ev, val) {
		if (val.value.length == 0) {
			this.options.role.setValue(cakephpConfig.roles.user);
		}
	},

	/**
	 * Observe when the user wants to see the password unscrumbled
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
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
	 * Observe when the user wants to see the password unscrumbled
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 */
	'{showCurrPwdButton} click': function(el, ev) {
		var password = this.getElement('js_field_current_password');
		var passwordClear = this.getElement('js_field_current_password_clear');


		// if the password is already hidden
		if (password.state.is('hidden')) {
			// hide the unscrambled password
			passwordClear.setState('hidden');
			// show the password field
			password.setState('ready');
			// unpush the button
			this.options.showCurrPwdButton.view.removeClass('selected');
		}
		else {
			// hide the password field
			password.setState('hidden');
			// display the unscrambled password
			passwordClear.setState('ready');
			passwordClear.setValue(password.getValue());
			// push the button
			this.options.showCurrPwdButton.view.addClass('selected');
		}
	},

	/**
	 * Observe when the user wants to generate a password
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 */
	'{genPwdButton} click': function(el, ev) {
		var value = passbolt.model.Secret.generate();
		this.getElement('js_field_password').setValue(value);
		this.getElement('js_field_password_clear').setValue(value);
		this.updateSecretEntropy(value);
	}

});

export default CreateForm;
