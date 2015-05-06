steal(
	'mad/controller/componentController.js',
	'app/controller/component/preferenceMenuController.js',
	'app/controller/component/preferenceBreadcrumbController.js',
	'app/controller/component/profileController.js',
	'app/controller/component/profileKeysController.js',
	'app/controller/form/user/createFormController.js',
	'app/controller/form/user/passwordFormController.js',
	'app/controller/form/user/avatarFormController.js',
	'app/view/template/preferenceWorkspace.ejs'
).then(function () {

		/*
		 * @class passbolt.controller.PreferenceWorkspaceController
		 * @inherits {mad.controller.ComponentController}
		 * @parent index
		 *
		 * @constructor
		 * Instanciates a new Preference Workspace Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {passbolt.controller.PreferenceWorkspaceController}
		 */
		mad.controller.ComponentController.extend('passbolt.controller.PreferenceWorkspaceController', /** @static */ {
			defaults: {
				'label': 'Preference',
				'templateUri': 'app/view/template/preferenceWorkspace.ejs'
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

				this.menuItems = Array();
				// Instanciate the preference menu
				this.menuItems['profile'] = new mad.model.Action({
					'id': uuid(),
					'label': __('My profile'),
					'cssClasses': ['selected'],
					'action': function () {
						mad.bus.trigger('request_profile_section', 'profile');
					}
				});
				this.menuItems['keys'] = new mad.model.Action({
					'id': uuid(),
					'label': __('Manage your keys'),
					'action': function () {
						mad.bus.trigger('request_profile_section', 'keys');
					}
				});

				this.preferenceWkMenu = new passbolt.controller.component.PreferenceMenuController('#js_wk_preference_menu', {
					menuItems : [
						this.menuItems['profile'],
						this.menuItems['keys']
					]
				});
				this.preferenceWkMenu.start();

				// Instanciate the main tabs controller
				this.preferenceTabsCtl = new mad.controller.component.TabController('#js_wk_preference_main', {
					'autoMenu': false // do not generate automatically the associated tab nav
				});
				this.preferenceTabsCtl.start();

				// Instantiate the password workspace breadcrumb controller
				this.breadcrumCtl = new passbolt.controller.component.PreferenceBreadcrumbController($('#js_wsp_preference_breadcrumb'), {});
				this.breadcrumCtl.start();
				this.breadcrumCtl.load();

				self.profileCtl = self.preferenceTabsCtl.addComponent(passbolt.controller.component.ProfileController, {
					'id': 'js_preference_wk_profile_controller',
					'label': 'profile',
					'user': passbolt.model.User.getCurrent()
				});

				self.profileKeysCtl = self.preferenceTabsCtl.addComponent(passbolt.controller.component.ProfileKeysController, {
					'id': 'js_preference_wk_profile_keys_controller',
					'label': 'keys'
				});
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
			'{mad.bus} request_profile_section': function (el, ev, section) {
				var tabId = null;
				switch (section) {
					case 'keys' :
						tabId = 'js_preference_wk_profile_keys_controller';
						break;
					case 'profile' :
						tabId = 'js_preference_wk_profile_controller';
						break;
				}
				if (tabId) {
					this.preferenceTabsCtl.enableTab(tabId);
					if (section == 'keys') {
						var userId = passbolt.model.User.getCurrent().id;
						mad.bus.trigger('passbolt.keys_preferences.init', userId);
					}
				}
				this.section = section;
				// Select corresponding section in the menu.
				this.preferenceWkMenu.selectItem(this.menuItems[this.section]);
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
				// Enable the target preference screen
				var prefScreenId = 'js_preference_wk_profile_controller';
				var prefContainer = mad.app.getComponent('js_wk_preference_main');
				prefContainer.enableTab(prefScreenId);
				this.section = 'profile';
				this.preferenceWkMenu.selectItem(this.menuItems['profile']);
			}

		});
	});
