import 'mad/component/component';
import 'app/view/template/component/settings_workspace_menu.ejs!';


/**
 * @inherits mad.Component
 * @parent index
 *
 * Our passbolt settings workspace menu controller
 *
 * @constructor
 * Creates a new settings workspace menu controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.SettingsWorkspaceMenu}
 */
var SettingsWorkspaceMenu = passbolt.component.SettingsWorkspaceMenu = mad.Component.extend('passbolt.component.SettingsWorkspaceMenu', /** @static */ {
	defaults: {
		label: 'Settings Workspace Menu',
		templateUri: 'app/view/template/component/settings_workspace_menu.ejs'
	}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
	afterStart: function () {
		this.options.sectionItems = {
			profile: {},
			keys: {}
		};
		// Manage edition action
		this.options.sectionItems['profile'].edit = new mad.component.Button($('#js_settings_wk_menu_edition_button'))
			.start();

		this.options.sectionItems['keys'].downloadPublic = new mad.component.Button($('#js_settings_wk_menu_download_public_key'))
			.start();

		this.options.sectionItems['keys'].downloadPrivate = new mad.component.Button($('#js_settings_wk_menu_download_private_key'))
			.start();

		this.on();
	},

	/* ************************************************************** */
	/* LISTEN TO THE APP EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user wants to edit an instance (Resource, User depending of the active workspace)
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'{sectionItems.profile.edit.element} click': function (el, ev) {
		mad.bus.trigger('request_profile_edition');
	},

	/**
	 * Observe when the user wants to download his public key.
	 * @param el
	 * @param ev
	 */
	'{sectionItems.keys.downloadPublic.element} click': function (el, ev) {
		mad.bus.trigger('passbolt.settings.download_public_key');
	},

	/**
	 * Observe when the user wants to download his private key.
	 * @param el
	 * @param ev
	 */
	'{sectionItems.keys.downloadPrivate.element} click': function (el, ev) {
		mad.bus.trigger('passbolt.settings.download_private_key');
	},

	/**
	 * Observe when the user changes section inside the workspace, and adjust the menu items accordingly
	 * @param el
	 * @param ev
	 * @param section
	 */
	'{mad.bus.element} request_settings_section': function(el, ev, section) {

		if (this.options.sectionItems[section] == 'undefined') {
			return;
		}

		for (let sectionName in this.options.sectionItems) {
			for (let item in this.options.sectionItems[sectionName]) {
				if (sectionName == section) {
					this.options.sectionItems[sectionName][item].setState('ready');
				}
				else {
					this.options.sectionItems[sectionName][item].setState('hidden');
				}
			}
		}
	}
});

export default SettingsWorkspaceMenu;
