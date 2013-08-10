steal(
	'mad/controller/appController.js',
	// the main workspaces of the application
	'app/controller/passwordWorkspaceController.js',
	'app/controller/peopleWorkspaceController.js',
	// common components of the application
	'app/controller/component/appMenuController.js',
	'app/controller/component/appFilterController.js',
	'app/controller/component/notificationController.js',
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
			// Instantiate the app menu controller
			this.menuCtl = new passbolt.controller.component.AppMenuController($('#js_menu'));
			this.menuCtl.start();

			// Instantiate the filter controller
			this.filterCtl = new passbolt.controller.component.AppFilterController($('#js_filter'), {});
			this.filterCtl.start();

			// Instantiate the notification controller
			this.notifCtl = new passbolt.controller.component.NotificationController($('#js_notificator'), {
				'state': 'hidden'
			});
			this.notifCtl.start();

			// Instantiate workspaces container tabs element to the app 
			this.workspacesCtl = new mad.controller.component.TabController($('#js_workspaces_container'));
			this.workspacesCtl.start();

			// Instantiate the password workspace component to the workspaces container
			var passwordWk = this.workspacesCtl.addComponent(passbolt.controller.PasswordWorkspaceController, {
				'id': 'js_passbolt_passwordWorkspace_controller',
				'label': 'password'
			});
			var peopleWk = this.workspacesCtl.addComponent(passbolt.controller.PeopleWorkspaceController, {
				'id': 'js_passbolt_peopleWorkspace_controller',
				'label': 'people'
			});

			// @todo move this into ready state
			this.workspacesCtl.enableTab('js_passbolt_passwordWorkspace_controller');
		},

		/**
		 * Called when the passbolt application is ready
		 * @return {void}
		 */
		'ready': function () {
			this._super();
			// @todo Il est bien puant ce ready, check the state management pour gerer ca
			mad.bus.trigger('app_ready');
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the user wants to switch to another workspace
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {string} workspaceId The target workspace
		 * @return {void}
		 */
		'{mad.bus} workspace_selected': function (el, event, workspaceId) {
			var workspacesContainer = mad.app.getComponent('js_workspaces_container');
			workspacesContainer.enableTab(workspaceId);
		}

	});
});