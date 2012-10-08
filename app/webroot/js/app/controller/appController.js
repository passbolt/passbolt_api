steal(
	MAD_ROOT + '/controller/appController.js',
	'app/controller/categoryController.js',
	'app/controller/resourceController.js',
	'app/controller/passwordWorkspaceController.js',
	'app/controller/component/menuController.js',
	'app/controller/component/notificationController.js',

	'app/model/category.js',
	'app/model/resource.js',

	'app/view/template/app.ejs'
).then(function ($) {

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

			// Add a notification controller
			var menuCtl = new passbolt.controller.component.MenuController($('#js_menu_controller'));
			menuCtl.render();

			// Add a notification controller
			var notifCtl = passbolt.controller.component.NotificationController.singleton($('#js_notif_controller'), {
				'state': 'hidden'
			});

			// Add a workspaces container tabs element to the app 
			var workspaces = new mad.controller.component.TabController($('#js_workspaces_container'));
			workspaces.render();

			// Add the password workspace component to the workspaces container
			// @todo addComponent is our factory, maybe more proper to do
			workspaces.addComponent(passbolt.controller.PasswordWorkspaceController, {
				'id': 'js_passbolt_passwordWorkspace_controller',
				'label': 'password'
			});
			workspaces.enableTab('js_passbolt_passwordWorkspace_controller');
		},

		/**
		 * Called when the passbolt application is ready
		 * @return {void}
		 */
		'ready': function () {
			this._super();

			// @dev BEGIN

//			// Create local resources for fixtures
//			var categories = passbolt.model.Category.getRoots({
//				children: true
//			}, function (request, response, categories) {
//				for(var i in categories) {
//					loadFixturedResources(categories[i]);
//				}
//			});

			mad.eventBus.trigger('app_ready');

			// test the exception catcher
			throw new mad.error.Error('Simulated exception to demonstrate the error handler system, and the notification system');

			// @dev END
		}

	});
});