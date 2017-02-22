import 'mad/component/component';
import 'mad/component/tab';
import 'app/component/settings_menu';
import 'app/component/settings_workspace_menu';
import 'app/component/breadcrumb/settings_breadcrumb';
import 'app/component/profile';
import 'app/component/keys';
import 'app/form/user/create';
import 'app/form/user/avatar';

import 'app/view/template/settings_workspace.ejs!';

/**
 * @inherits {mad.Component}
 * @parent index
 *
 * @constructor
 * Instanciates a new Settings Workspace
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.SettingsWorkspace}
 */
var SettingsWorkspace = passbolt.component.SettingsWorkspace = mad.Component.extend('passbolt.component.SettingsWorkspace', /** @static */ {
	defaults: {
		label: 'Settings',
		templateUri: 'app/view/template/settings_workspace.ejs',
		sections : [
			'profile',
			'keys'
		],
		// Override the silentLoading parameter.
		silentLoading: false
	}
}, /** @prototype */ {

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
	afterStart: function() {
		var self = this;
		this.section = '';

		// Instantiate the primary workspace menu controller outside of the workspace container, destroy it when the workspace is destroyed
		var primWkMenu = mad.helper.Component.create(
			$('#js_wsp_primary_menu_wrapper'),
			'last',
			passbolt.component.SettingsWorkspaceMenu,
			{}
		);
		primWkMenu.start();

		// Instantiate the settings menu
		this.settingsWkMenuItems = {
			profile: new mad.model.Action({
				id: uuid(),
				label: __('My profile'),
				action: function () {
					mad.bus.trigger('request_settings_section', 'profile');
				}
			}),
			keys: new mad.model.Action({
				id: uuid(),
				label: __('Manage your keys'),
				action: function () {
					mad.bus.trigger('request_settings_section', 'keys');
				}
			})
		};
		this.settingsWkMenu = new passbolt.component.SettingsMenu('#js_wk_settings_menu', {
			menuItems : this.settingsWkMenuItems
		}).start();

		// Instantiate the main tabs controller
		this.settingsTabsCtl = new mad.component.Tab('#js_wk_settings_main', {
			autoMenu: false // do not generate automatically the associated tab nav
		});
		this.settingsTabsCtl.start();

		// Instantiate the password workspace breadcrumb controller
		this.breadcrumCtl = new passbolt.component.SettingsBreadcrumb($('#js_wsp_settings_breadcrumb'), {})
			.start()
			.load();

		this.profileCtl = self.settingsTabsCtl.addComponent(passbolt.component.Profile, {
			id: 'js_settings_wk_profile_controller',
			label: 'profile',
			user: passbolt.model.User.getCurrent()
		});

		this.profileKeysCtl = self.settingsTabsCtl.addComponent(passbolt.component.Keys, {
			id: 'js_settings_wk_profile_keys_controller',
			label: 'keys'
		});
	},

	/**
	 * Destroy the workspace.
	 */
	destroy: function() {
		// Be sure that the primary workspace menu controller will be destroyed also.
		$('#js_wsp_primary_menu_wrapper').empty();
		// Destroy the breadcrumb too.
		$('#js_wsp_settings_breadcrumb').empty();

		this._super();
	},

	/* ************************************************************** */
	/* LISTEN TO THE APP EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user requests a profile edition
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'{mad.bus.element} request_profile_edition': function (el, ev) {
		// Current user.
		var user = passbolt.model.User.getCurrent();

		// get the dialog
		var dialog = new mad.component.Dialog(null, {
			label: __('Edit profile'),
			cssClasses : ['edit-profile-dialog','dialog-wrapper']
		}).start();
		// attach the component to the dialog
		var form = dialog.add(passbolt.form.user.Create, {
			data: user,
			action: 'edit',
			callbacks : {
				submit: function (data) {
					user.attr(data['passbolt.model.User']).save(
						// Success.
						function() {
							dialog.remove();
						},
						// Error.
						function(v) {
							form.showErrors(JSON.parse(v.responseText)['body']);
						}
					);
				}
			}
		});
		form.load(user);
	},

	/**
	 * Observe when the user requests an avatar edition
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'{mad.bus.element} request_profile_avatar_edition': function (el, ev, user) {
		// get the dialog
		var dialog = new mad.component.Dialog(null, {label: __('Edit Avatar')})
			.start();
		// attach the component to the dialog
		var form = dialog.add(passbolt.form.user.Avatar, {
			data: user,
			callbacks : {
				submit: function (data) {
					var $fileField = $('#js_field_avatar');
					user.saveAvatar($fileField[0].files[0]);
					dialog.remove();
				}
			}
		});
		form.load(user);
	},

	/**
	 * Observe when the user requests a section.
	 * @param el
	 * @param ev
	 * @param section
	 */
	'{mad.bus.element} request_settings_section': function (el, ev, section) {
		var tabId = null,
			sectionIsValid = $.inArray(section, this.options.sections) != -1;

		if (sectionIsValid) {
			switch (section) {
				case 'keys' :
					tabId = 'js_settings_wk_profile_keys_controller';
					break;
				case 'profile' :
					tabId = 'js_settings_wk_profile_controller';
					break;
			}
			if (tabId) {
				this.settingsTabsCtl.enableTab(tabId);
			}

			// Set class on top container.
			$('#container')
				.removeClass(this.options.sections.join(" "))
				.addClass(section);

			this.section = section;
			// Select corresponding section in the menu.
			this.settingsWkMenu.selectItem(this.settingsWkMenuItems[this.section]);
		}
	},

	/* ************************************************************** */
	/* LISTEN TO THE STATE CHANGES */
	/* ************************************************************** */

	/**
	 * The application is ready.
	 * @param {boolean} go Enter or leave the state
	 */
	stateReady: function (go) {
		// Load profile section by default.
		mad.bus.trigger('request_settings_section', 'profile');
	},

	/**
	 * state disabled.
	 * @param go
	 */
	stateDisabled: function (go) {
		this._super(go);
		// Remove container class.
		$('#container')
			.removeClass(this.options.sections.join(" "));
	},

	/**
	 * state hidden.
	 * @param go
	 */
	stateHidden: function (go) {
		this._super(go);
		// Remove container class.
		$('#container')
			.removeClass(this.options.sections.join(" "));
	}
});
export default SettingsWorkspace;
