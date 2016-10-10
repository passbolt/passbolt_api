import 'mad/view/component/tree';
import 'app/component/sidebar';
import 'app/view/component/user_sidebar';
import 'app/view/template/component/user_sidebar.ejs!';

/*
 * @class passbolt.component.UserSidebar
 * @inherits mad.component.Sidebar
 * @parent index
 *
 * @constructor
 * Creates a new User sidebar component
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.UserSidebar}
 */
var UserSidebar = passbolt.component.UserSidebar = passbolt.component.Sidebar.extend('passbolt.component.UserSidebar', /** @static */ {

	defaults: {
        // Label.
		label: 'User Details Controller',
        // View class to be used.
		viewClass: passbolt.view.component.UserSidebar,
        // Template.
		templateUri: 'app/view/template/component/user_sidebar.ejs'
	}

}, /** @prototype */ {

	/**
	 * before start hook.
	 */
	beforeRender: function () {
		this._super();
		this.setViewData('user', this.options.selectedItem);
	},


	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * Listen when a user clicks on copy public key.
	 */
	' request_copy_publickey': function(el, ev) {
		// Get secret out of Resource object.
		var gpgKey = this.options.selectedItem.Gpgkey.key;
		// Build data.
		var data = {
			name : 'Public key',
			data : gpgKey
		};
		// Request decryption. (delegated to plugin).
		mad.bus.trigger('passbolt.clipboard', data);
	}

});

export default UserSidebar;
