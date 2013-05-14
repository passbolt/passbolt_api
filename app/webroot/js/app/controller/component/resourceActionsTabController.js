steal(
	// 'app/view/template/component/resourceActionsTab.ejs'
).then(function () {

	/*
	 * @class passbolt.controller.ResourceActionsTabController
	 * @inherits mad.controller.component.ComponentController
	 * @parent index 
	 * 
	 * @constructor
	 * Creates new ResourceActionsTabController
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.ResourceActionsTabController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.ResourceActionsTabController', /** @static */ {

		'defaults': {
			'label': 'Resource Actions Tab Controller',
			// 'viewClass': passbolt.view.component.ResourceDetails,
			// the resource to bind the component on
			'resource': null
		}

	}, /** @prototype */ {
		
		// Constructor like
		'init': function (el, options) {
			this._super(el, options);
			this.render();
return;
			// Add the app menu controller
			this.menuCtl = new mad.controller.component.MenuController($('#js_menu'));
			this.menuCtl.render();
			this.menuCtl.initMenuItems();
			
			// Add a workspaces container tabs element to the app 
			this.workspacesCtl = new mad.controller.component.TabController($('#js_workspaces_container'));
			this.workspacesCtl.render();

			// Add the password workspace component to the workspaces container
			// @todo addComponent is our factory, maybe more proper to do
			var passwordWk = this.workspacesCtl.addComponent(passbolt.controller.PasswordWorkspaceController, {
				'id': 'js_passbolt_passwordWorkspace_controller',
				'label': 'password'
			});
			var peopleWk = this.workspacesCtl.addComponent(passbolt.controller.PeopleWorkspaceController, {
				'id': 'js_passbolt_peopleWorkspace_controller',
				'label': 'people'
			});
			// select the default tab
			this.workspacesCtl.enableTab('js_passbolt_passwordWorkspace_controller');
			
			this._super(el, options);
		},
		
		/**
		 * Load details of a resource
		 * @param {passbolt.model.Resource} resource The resource to load
		 * @return {void}
		 */
		'load': function (resource) {
			// push the new resource in the options to be able to listen the resource
			// change in the function name
			this.options.resource = resource;
			// pass the new resource to the view
			this.setViewData('resource', resource);
			// refresh the view
			this.refresh();
			// // on
			// this.on();
		}
		 
	});

});