steal(
	'mad/controller/appController.js',

	'app/controller/passwordWorkspaceController.js',
	'app/controller/peopleWorkspaceController.js',

	'app/controller/component/appMenuController.js',
	'app/controller/component/appFilterController.js',
	'app/controller/component/notificationController.js',
	// the ressources workspace models
	'app/model/category.js',
	'app/model/resource.js',
	'app/model/filter.js',
	// the application template
	'app/view/template/app.ejs'
).then(function () {

	/*
	 * @class passbolt.controller.AppController
	 * @inherits [mad.controller.AppController,mad.core.Singleton]
	 * @parent index
	 * 
	 * The passbolt application controller.
	 */
	mad.controller.AppController.extend('passbolt.controller.AppController', {

		// constructor of the Class
		'init': function (el, options) {
			this._super(el, options);
			this.render();

			// Add the app menu controller
			var menuCtl = new passbolt.controller.component.AppMenuController($('#js_menu'));
			menuCtl.render();
			menuCtl.initMenuItems();

			// Add the filter controller
			var filterCtl = new passbolt.controller.component.AppFilterController($('#js_filter'), {});
			filterCtl.render();

			// Add the notification controller
			var notifCtl = passbolt.controller.component.NotificationController.singleton($('#js_notificator'), {
				'state': 'hidden'
			});

			// Add a workspaces container tabs element to the app 
			var wksCtl = new mad.controller.component.TabController($('#js_workspaces_container'));
			wksCtl.render();

			// Add the password workspace component to the workspaces container
			// @todo addComponent is our factory, maybe more proper to do
			var passwordWk = wksCtl.addComponent(passbolt.controller.PasswordWorkspaceController, {
				'id': 'js_passbolt_passwordWorkspace_controller',
				'label': 'password'
			});
			var peopleWk = wksCtl.addComponent(passbolt.controller.PeopleWorkspaceController, {
				'id': 'js_passbolt_peopleWorkspace_controller',
				'label': 'people'
			});

			wksCtl.enableTab('js_passbolt_passwordWorkspace_controller');
		},

		/**
		 * Called when the passbolt application is ready
		 * @return {void}
		 */
		'ready': function () {
			this._super();
			// @todo Il est bien puant ce ready, check the state management pour gerer ca
			mad.eventBus.trigger('app_ready');
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
		'{passbolt.eventBus} workspace_selected': function (el, event, workspaceId) {
			var workspacesContainer = mad.app.getComponent('js_workspaces_container');
			workspacesContainer.enableTab(workspaceId);
		}

	});
});