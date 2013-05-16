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
			'label': null,
			'resource': null
		}

	}, /** @prototype */ {
		
		'afterStart': function() {
			var self = this;
			this.menu = new mad.controller.component.MenuController($('#js_resource_actions_tab_menu'));
			this.menu.start();
			
			var menuItems = [
				new mad.model.Action({
					'id': uuid(),
					'label': 'edit',
					'action': function () {
						mad.bus.trigger('workspace_selected', 'js_resource_create');
					}
				}), new mad.model.Action({
					'id': uuid(),
					'label': 'share',
					'action': function () {
						mad.bus.trigger('workspace_selected', 'js_resource_create');
					}
				}), new mad.model.Action({
					'id': uuid(),
					'label': 'organize',
					'action': function () {
						mad.bus.trigger('workspace_selected', 'js_resource_create');
					}
				}), new mad.model.Action({
					'id': uuid(),
					'label': 'logs',
					'action': function () {
						mad.bus.trigger('workspace_selected', 'js_category_create');
					}
				})
			];
			this.menu.load(menuItems);
			
			// Instantiate workspaces container tabs element to the app 
			this.container = new mad.controller.component.TabController($('#js_resource_actions_tab_container'));
			this.container.start();

			var cp1 = this.container.addComponent(passbolt.controller.form.resource.CreateFormController, {
				'id': 'js_resource_create',
				'data': this.options.resource,
				// 'state': 'hidden',
				'callbacks' : {
					'submit': function (data) {
						self.options.resource.attr(data['passbolt.model.Resource'])
							.save();
						// popup.remove();
					}
				}
			});
			cp1.start();
			cp1.load(this.options.resource);
		},
		
		// Constructor like
		'init': function (el, options) {
			this._super(el, options);
			
			
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