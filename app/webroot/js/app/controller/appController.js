steal(
	'mad/controller/appController.js',
	// the main workspaces of the application
	'app/controller/settingsWorkspaceController.js',
	'app/controller/component/passwordWorkspaceMenuController.js',
	'app/controller/passwordWorkspaceController.js',
	'app/controller/peopleWorkspaceController.js',
	'app/controller/component/peopleWorkspaceMenuController.js',
	'app/controller/component/settingsWorkspaceMenuController.js',
	// common components of the application
	'app/controller/component/appNavigationLeftController.js',
	'app/controller/component/appNavigationRightController.js',
	'app/controller/component/appFilterController.js',
	'app/controller/component/profileDropdownController.js',
	'app/controller/component/notificationController.js',
	'app/controller/component/loadingBarController.js',
	// the ressources workspace models
	'app/model/category.js',
	'app/model/favorite.js',
	'app/model/resource.js',
	'app/model/filter.js',
	// the application template
	'app/view/template/app.ejs'
).then(function () {

	/*
	 * @class passbolt.controller.AppController
	 * @inherits mad.controller.AppController
	 * @parent index
	 * 
	 * The passbolt application controller.
	 */
	mad.controller.AppController.extend('passbolt.controller.AppController', /** @static */ {
		'defaults': {
			// List of available workspaces.
			'workspaces':[
				'password',
				'people',
				'settings'
			]
		}

	}, /** @prototype */ {

		/**
		 * After start hook.
		 * Initialise component of the application
		 * @return {void}
		 */
		'afterStart': function() {
			var self = this;

			// Instantiate the app navigation left controller
			this.navLeftCtl = new passbolt.controller.component.AppNavigationLeftController($('#js_app_navigation_left'));
			this.navLeftCtl.start();

			// Instantiate the app navigation right controller
			this.navRightCtl = new passbolt.controller.component.AppNavigationRightController($('#js_app_navigation_right'));
			this.navRightCtl.start();

			// Instantiate the filter controller
			this.filterCtl = new passbolt.controller.component.AppFilterController($('#js_app_filter'), {});
			this.filterCtl.start();

			// Get logged in user.
			passbolt.model.User.findOne({
				'id': mad.Config.read('user.id')
			}).then(function(user) {
				// Set current user.
				passbolt.model.User.setCurrent(user);
				// Instantiate the profile controller.
				self.profileDropDownCtl = new passbolt.controller.component.ProfileDropdownController($('#js_app_profile_dropdown'), {
					'user': user
				});
				self.profileDropDownCtl.start();
			});

			// Instantiate the notification controller
			this.notifCtl = new passbolt.controller.component.NotificationController($('#js_app_notificator'), {});

			// Instantiate the laoding bar controller
			this.loadingBarCtl = new passbolt.controller.component.LoadingBarController($('#js_app_loading_bar'), {});
			this.loadingBarCtl.start();

			// Instantiate workspaces container tabs element to the app
			this.workspacesCtl = new mad.controller.component.TabController($('#js_app_panel_main'), {
				'autoMenu': false // do not generate automatically the associated tab nav
			});
			this.workspacesCtl.start();

			// Instantiate the password workspace component and add it to the workspaces container
			this.passwordWk = this.workspacesCtl.addComponent(passbolt.controller.PasswordWorkspaceController, {
				'id': 'js_passbolt_passwordWorkspace_controller',
				'label': 'password'
			});
			var selectedResources = this.passwordWk.getSelectedResources();

			// Instantiate the people workspace component and add it to the workspaces container
			this.peopleWk = this.workspacesCtl.addComponent(passbolt.controller.PeopleWorkspaceController, {
				'id': 'js_passbolt_peopleWorkspace_controller',
				'label': 'people'
			});
			var selectedUsers = this.peopleWk.getSelectedUsers();
			var selectedGroups = this.peopleWk.getSelectedGroups();

			// Instantiate the settings workspace component and add it to the workspaces container
			this.settingsWk = this.workspacesCtl.addComponent(passbolt.controller.SettingsWorkspaceController, {
				'id': 'js_passbolt_settingsWorkspace_controller',
				'label': 'settings'
			});

			// Instantiate workspaces menus container tabs element to the app
			this.workspacesMenusCtl = new mad.controller.component.TabController($('#js_wsp_primary_menu'), {
				'autoMenu': false // do not generate automatically the associated tab nav
			});
			this.workspacesMenusCtl.start();

			// Instantiate the password workspace menu component and add it to the workspaces menus container
			this.passwordWkMenu = this.workspacesMenusCtl.addComponent(passbolt.controller.component.PasswordWorkspaceMenuController, {
				'id': 'js_passbolt_passwordWorkspaceMenu_controller',
				'label': 'password',
				'selectedRs': selectedResources
			});

			// Instantiate the people workspace menu component and add it to the workspaces menus container
			this.peopleWkMenu = this.workspacesMenusCtl.addComponent(passbolt.controller.component.PeopleWorkspaceMenuController, {
				'id': 'js_passbolt_peopleWorkspaceMenu_controller',
				'label': 'people',
				'selectedUsers': selectedUsers,
				'selectedGroups': selectedGroups
			});

			// Instantiate the people workspace menu component and add it to the workspaces menus container
			this.settingsWkMenu = this.workspacesMenusCtl.addComponent(passbolt.controller.component.SettingsWorkspaceMenuController, {
				'id': 'js_passbolt_settingsWorkspaceMenu_controller',
				'label': 'settings'
			});
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the user wants to switch to another workspace
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {string} workspace The target workspace
		 * @return {void}
		 */
		'{mad.bus} workspace_selected': function (el, event, workspace) {
			var wspMenuId = 'js_passbolt_' + workspace + 'WorkspaceMenu_controller';
			var primWkMenuContainer = mad.app.getComponent('js_wsp_primary_menu');

			// The primary workspace is a specific case.
			// It is only displayed for the password and people workpsaces.
			var workspaceIsValid = $.inArray(workspace, this.options.workspaces) != -1;
			if (workspaceIsValid) {
				if (primWkMenuContainer.state.is('hidden')) {
					primWkMenuContainer.setState('ready');
				}
				// Enable the corresponding workspace menu.
				primWkMenuContainer.enableTab(wspMenuId);

				// Set class on top container.
				$('#container')
					.removeClass(this.options.workspaces.join(" "))
					.addClass(workspace);
			}
			// In any other case, hide the primary and secondary workspace.
			else {
				primWkMenuContainer.setState('hidden');
			}

			// Enable the target workspace.
			var wspId = 'js_passbolt_' + workspace + 'Workspace_controller';
			var workspacesContainer = mad.app.getComponent('js_app_panel_main');
			workspacesContainer.enableTab(wspId);
		},

		/**
		 * Observe when the user requests a dialog to be opened.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {string} label The label of the dialog
		 * @param {array} options (optional) Options to give to the dialog controller
		 * @return {void}
		 */
		'{mad.bus} request_dialog': function (el, ev, options) {
			var options = options || {};
			new mad.controller.component.DialogController(null, options).start();
		},

		/**
		 * Observe when the user wants to close the latest dialog.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{mad.bus} request_dialog_close_latest': function (el, ev, options) {
			mad.controller.component.DialogController.closeLatest();
		},

		/**
		 * Observe when the app is ready.
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{mad.bus} app_ready': function (el, ev) {
			// Remove the loading component.
			$('html').removeClass('loading');
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
			// Select the password workspace
			mad.bus.trigger('workspace_selected', 'password');
			mad.bus.trigger('app_ready');
		}

	});
});
