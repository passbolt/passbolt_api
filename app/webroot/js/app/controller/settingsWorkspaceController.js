steal(
	'mad/controller/componentController.js',
	'app/controller/component/settingsMenuController.js',
	'app/controller/component/settingsBreadcrumbController.js',
	'app/controller/component/profileController.js',
	'app/controller/component/keysController.js',
	'app/controller/form/user/createFormController.js',
	'app/controller/form/user/passwordFormController.js',
	'app/controller/form/user/avatarFormController.js',
	'app/view/template/settingsWorkspace.ejs'
).then(function () {

		/*
		 * @class passbolt.controller.SettingsWorkspaceController
		 * @inherits {mad.controller.ComponentController}
		 * @parent index
		 *
		 * @constructor
		 * Instanciates a new Settings Workspace Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {passbolt.controller.SettingsWorkspaceController}
		 */
		mad.controller.ComponentController.extend('passbolt.controller.SettingsWorkspaceController', /** @static */ {
			defaults: {
				'label': 'Settings',
				'templateUri': 'app/view/template/settingsWorkspace.ejs',
				'sections' : [
					'profile',
					'keys'
				]
			}
		}, /** @prototype */ {

			/**
			 * Called right after the start function
			 * @return {void}
			 * @see {mad.controller.ComponentController}
			 */
			'afterStart': function() {
				var self = this;
				this.section = '';

				// Instantiate the primary workspace menu controller outside of the workspace container, destroy it when the workspace is destroyed
				var component = mad.helper.ComponentHelper.create(
					$('#js_wsp_primary_menu_wrapper'),
					'last',
					passbolt.controller.component.SettingsWorkspaceMenuController,
					{}
				);
				component.start();

				this.menuItems = Array();
				// Instantiate the settings menu
				this.menuItems['profile'] = new mad.model.Action({
					'id': uuid(),
					'label': __('My profile'),
					'action': function () {
						mad.bus.trigger('request_settings_section', 'profile');
					}
				});
				this.menuItems['keys'] = new mad.model.Action({
					'id': uuid(),
					'label': __('Manage your keys'),
					'action': function () {
						mad.bus.trigger('request_settings_section', 'keys');
					}
				});

				this.settingsWkMenu = new passbolt.controller.component.SettingsMenuController('#js_wk_settings_menu', {
					menuItems : [
						this.menuItems['profile'],
						this.menuItems['keys']
					]
				});
				this.settingsWkMenu.start();

				// Instanciate the main tabs controller
				this.settingsTabsCtl = new mad.controller.component.TabController('#js_wk_settings_main', {
					'autoMenu': false // do not generate automatically the associated tab nav
				});
				this.settingsTabsCtl.start();

				// Instantiate the password workspace breadcrumb controller
				this.breadcrumCtl = new passbolt.controller.component.SettingsBreadcrumbController($('#js_wsp_settings_breadcrumb'), {});
				this.breadcrumCtl.start();
				this.breadcrumCtl.load();

				self.profileCtl = self.settingsTabsCtl.addComponent(passbolt.controller.component.ProfileController, {
					'id': 'js_settings_wk_profile_controller',
					'label': 'profile',
					'user': passbolt.model.User.getCurrent()
				});

				self.profileKeysCtl = self.settingsTabsCtl.addComponent(passbolt.controller.component.KeysController, {
					'id': 'js_settings_wk_profile_keys_controller',
					'label': 'keys'
				});
			},

			/**
			 * Destroy the workspace.
			 */
			'destroy': function() {
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
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'{mad.bus} request_profile_edition': function (el, ev) {
				// @todo #PASSBOLT-985 fixed in future canJs.
				if (!this.element) return;

				var self = this;

				var user = passbolt.model.User.getCurrent();

				// get the dialog
				var dialog = new mad.controller.component.DialogController(null, {label: __('Edit User')})
					.start();
				// attach the component to the dialog
				var form = dialog.add(passbolt.controller.form.user.CreateFormController, {
					data: user,
					action: 'edit',
					callbacks : {
						submit: function (data) {
							user.attr(data['passbolt.model.User']).save();
							dialog.remove();
						}
					}
				});
				form.load(user);
			},

			/**
			 * Observe when the user requests a password edition
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'{mad.bus} request_user_password_edition': function (el, ev, user) {
				// @todo #PASSBOLT-985 fixed in future canJs.
				if (!this.element) return;

				var self = this;

				// get the dialog
				var dialog = new mad.controller.component.DialogController(null, {label: __('Edit User Password')})
					.start();
				// attach the component to the dialog
				var form = dialog.add(passbolt.controller.form.user.PasswordFormController, {
					data: user,
					callbacks : {
						submit: function (data) {
							user.attr(data['passbolt.model.User'])
								.savePassword();
							dialog.remove();
						}
					}
				});
				form.load(user);
			},

			/**
			 * Observe when the user requests an avatar edition
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'{mad.bus} request_profile_avatar_edition': function (el, ev, user) {
				// @todo #PASSBOLT-985 fixed in future canJs.
				if (!this.element) return;

				var self = this;

				// get the dialog
				var dialog = new mad.controller.component.DialogController(null, {label: __('Edit Avatar')})
					.start();
				// attach the component to the dialog
				var form = dialog.add(passbolt.controller.form.user.AvatarFormController, {
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
			'{mad.bus} request_settings_section': function (el, ev, section) {
				// @todo #PASSBOLT-985 fixed in future canJs.
				if (!this.element) return;

				var tabId = null;
				var sectionIsValid = $.inArray(section, this.options.sections) != -1;
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
					this.settingsWkMenu.selectItem(this.menuItems[this.section]);
				}
			},

			/* ************************************************************** */
			/* LISTEN TO THE STATE CHANGES */
			/* ************************************************************** */

			/**
			 * The application is ready.
			 * @param {boolean} go Enter or leave the state
			 * @return {void}
			 */
			'stateReady': function (go) {
				// Load profile section by default.
				mad.bus.trigger('request_settings_section', 'profile');
			},

			/**
			 * state disabled.
			 * @param go
			 */
			'stateDisabled': function (go) {
				this._super(go);
				// Remove container class.
				$('#container')
					.removeClass(this.options.sections.join(" "));
			},

			/**
			 * state hidden.
			 * @param go
			 */
			'stateHidden': function (go) {
				this._super(go);
				// Remove container class.
				$('#container')
					.removeClass(this.options.sections.join(" "));
			}
		});
	});
