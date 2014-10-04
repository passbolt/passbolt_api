steal(
	'mad/controller/appController.js',
	// the main workspaces of the application
	'app/controller/component/passwordWorkspaceMenuController.js',
	'app/controller/passwordWorkspaceController.js',
	'app/controller/peopleWorkspaceController.js',
	'app/controller/component/peopleWorkspaceMenuController.js',
	// common components of the application
	'app/controller/component/appNavigationLeftController.js',
	'app/controller/component/appNavigationRightController.js',
	'app/controller/component/appFilterController.js',
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

	}, /** @prototype */ {

		/**
		 * After start hook.
		 * Initialise component of the application
		 * @return {void}
		 */
		'afterStart': function() {
			// Instantiate the app navigation left controller
			this.navLeftCtl = new passbolt.controller.component.AppNavigationLeftController($('#js_app_navigation_left'));
			this.navLeftCtl.start();

			// Instantiate the app navigation right controller
			this.navRightCtl = new passbolt.controller.component.AppNavigationRightController($('#js_app_navigation_right'));
			this.navRightCtl.start();

			// Instantiate the filter controller
			this.filterCtl = new passbolt.controller.component.AppFilterController($('#js_app_filter'), {});
			this.filterCtl.start();

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
			var selectedResources = this.passwordWk.getSelectedResources()
			// Instantiate the people workspace component and add it to the workspaces container
			this.peopleWk = this.workspacesCtl.addComponent(passbolt.controller.PeopleWorkspaceController, {
				'id': 'js_passbolt_peopleWorkspace_controller',
				'label': 'people'
			});
			var selectedUsers = this.peopleWk.getSelectedUsers()
			var selectedGroups = this.peopleWk.getSelectedGroups()

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
			// Enable the target workspace.
			var wspId = 'js_passbolt_' + workspace + 'Workspace_controller';
			var workspacesContainer = mad.app.getComponent('js_app_panel_main');
			workspacesContainer.enableTab(wspId);
			// Enable the corresponding workspace menu.
			var wspMenuId = 'js_passbolt_' + workspace + 'WorkspaceMenu_controller';
			var workspacesMenusContainer = mad.app.getComponent('js_wsp_primary_menu');
			workspacesMenusContainer.enableTab(wspMenuId);
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
			mad.bus.trigger('workspace_selected', 'password');
			mad.bus.trigger('app_ready');

			// Filter the view with all items order by modified date.
			var filter = new passbolt.model.Filter({
				'label': __('All items'),
				'order': 'modified'
			});
			mad.bus.trigger('filter_resources_browser', filter);
		}

	});
});
