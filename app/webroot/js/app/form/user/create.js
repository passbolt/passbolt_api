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
		action: 'create',
		templateUri: 'app/view/template/form/user/create.ejs'
	}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * Initialize the form elements.
	 * @see {mad.Component}
	 */
	afterStart: function () {
		// temporary for update demonstration
		this.options.data.User = this.options.data.User || {};

		// Is the user an admin.
		var isAdmin = passbolt.model.User.getCurrent().Role.name == 'admin' ? true : false;

		// Check if current user is editing his own profile.
		var editingOwnProfile = false;
		if (this.options.data != undefined && this.options.data.id == passbolt.model.User.getCurrent().id) {
			editingOwnProfile = true;
		}

		// Active field.
		var activeField = this.addElement(
			new mad.form.Textbox($('#js_field_user_active'), {
				modelReference: 'passbolt.model.User.active'
			}).start()
		);

		// Add user first name field.
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

		// Role box. (only for admins).
		if (isAdmin === true) {
			// Build roles data.
			var roles = {};
			roles[cakephpConfig.roles.admin] = __('This user is an administrator');
			roles[cakephpConfig.roles.user] = __('This user is a normal user');

			// Classes to add on each field
			var valueClasses = {};
			valueClasses[cakephpConfig.roles.admin] = 'role-admin';
			valueClasses[cakephpConfig.roles.user] = 'role-user';

			// Role component.
			this.options.role = new mad.form.Checkbox($('#js_field_role_id'), {
					name: 'role_id',
					modelReference: 'passbolt.model.User.role_id',
					availableValues: roles,
					valueClasses: valueClasses
				}
			).start();

			// Add role element to form, with feedback.
			this.addElement(
				this.options.role,
				new mad.form.Feedback($('#js_field_role_id_feedback'), {}).start()
			);

			// Hide everything that is not admin role.
			this.options.role.setValue(cakephpConfig.roles.user);
			$('input[type=checkbox]', $('#js_field_role_id')).not("[value='" + cakephpConfig.roles.admin + "']").hide().next('label').hide();

			// If editing his own profile, set the field as disabled.
			if (editingOwnProfile == true) {
				$('input[type=checkbox]', $('#js_field_role_id')).attr('disabled', true)
				$('#js_field_role_id').parent().addClass('disabled');
			}
		}


		// Add resource username field
		this.addElement(
			new mad.form.Textbox($('#js_field_username'), {
				modelReference: 'passbolt.model.User.username'
			}).start(),
			new mad.form.Feedback($('#js_field_username_feedback'), {}).start()
		);

		// Rebind controller events
		this.on();
	},


	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when a role is changed. If no role is selected, select user as default.
	 * @param el
	 * @param ev
	 * @param val
	 */
	'{role.element} changed': function(el, ev, val) {
        var adminSelected = $.inArray(cakephpConfig.roles.admin, val.value);
        if (adminSelected !== -1) {
            this.options.role.setValue(cakephpConfig.roles.admin);
        }
        else {
            this.options.role.setValue(cakephpConfig.roles.user);
        }
	}
});

export default CreateForm;
