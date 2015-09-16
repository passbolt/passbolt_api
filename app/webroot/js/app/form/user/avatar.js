import 'mad/form/form';
import 'app/view/template/form/user/avatar.ejs!';

passbolt.form.user = passbolt.form.user ? passbolt.form.user : {};

/**
 * @inherits {mad.Form}
 * @parent index
 *
 * @constructor
 * Instanciate a User Avatar Form
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.form.user.Avatar}
 */
var AvatarForm = passbolt.form.user.Avatar = mad.Form.extend('passbolt.form.user.Avatar', /** @static */ {
	defaults: {
		templateBased: true
	}
}, /** @prototype */ {

	/**
	 * After start hook.
	 * Create the form elements
	 *
	 * @return {void}
	 */
	afterStart: function () {

		// Rebind controller events
		this.on();
	}

});
export default AvatarForm;