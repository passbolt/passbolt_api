import 'mad/form/form';

passbolt.form.secret = passbolt.form.secret || {};

/**
 * @class passbolt.form.secret.Create
 * @inherits {mad.form.FormController}
 * @parent index
 *
 * @constructor
 * Instanciate a Secret Create Form Controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.form.secret.Create}
 */
var Create = passbolt.form.secret.Create = mad.Form.extend('passbolt.form.secret.Create', /** @static */ {

	defaults: {
		templateBased: false,
		// @todo should be dynamic functions of creation or update
		action: 'create',
		secret_i: null
	}
	
}, /** @prototype */ {

	/**
	 * After start hook.
	 * Create the form elements
	 */
	afterStart: function () {
		// Add secret id hidden field.
		this.addElement(
			new mad.form.Textbox($('#js_field_secret_id_' + this.options.secret_i), {
				modelReference: 'passbolt.model.Secret.id',
				validate: false
			}).start()
		);

		// Add secret user id hidden field.
		this.addElement(
			new mad.form.Textbox($('#js_field_secret_user_id_' + this.options.secret_i), {
				modelReference: 'passbolt.model.Secret.user_id',
				validate: false
			}).start()
		);

		// Add secret data hidden field.
		this.addElement(
			new mad.form.Textbox($('#js_field_secret_data_' + this.options.secret_i), {
				modelReference: 'passbolt.model.Secret.data',
				validate: false
			}).start()
		);

		// Rebind controller events
		this.on();
	}
	
});

export default Create;
