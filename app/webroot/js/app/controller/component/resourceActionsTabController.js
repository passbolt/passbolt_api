steal(
	'app/controller/component/permissionsController.js'
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
			
			// Instantiate the menu which will rule the tab container
			this.menu = new mad.controller.component.MenuController($('#js_resource_actions_tab_menu'));
			this.menu.start();
			var menuItems = [
				new mad.model.Action({ 'id': uuid(), 'label': __('edit'), 'action': function() { self.container.enableTab('js_resource_create'); } }),
				new mad.model.Action({ 'id': uuid(), 'label': __('share'), 'action': function() { self.container.enableTab('js_permission'); } }),
				new mad.model.Action({ 'id': uuid(), 'label': __('organize'), 'action': function() { self.container.enableTab('js_resource_create'); } }),
				new mad.model.Action({ 'id': uuid(), 'label': __('logs'), 'action': function() { self.container.enableTab('js_resource_create'); } })
			];
			this.menu.load(menuItems);
			
			// Instantiate tab container which will contain the resource's tools
			this.container = new mad.controller.component.TabController($('#js_resource_actions_tab_container'));
			this.container.start();

			// Add the edition form controller to the tab
			var editFormCtl = this.container.addComponent(passbolt.controller.form.resource.CreateFormController, {
				'id': 'js_resource_create',
				'data': this.options.resource,
				'state': 'hidden',
				'callbacks' : {
					'submit': function (data) {
						self.options.resource.attr(data['passbolt.model.Resource'])
							.save();
						// popup.remove();
					}
				}
			});
			editFormCtl.start();
			editFormCtl.load(this.options.resource);
			
			// Add the permission controller to the tab
			var permCtl = this.container.addComponent(passbolt.controller.component.PermissionsController, {
				'id': 'js_permission',
				'resource': this.options.resource
				// 'state': 'hidden'
			});
			permCtl.start();
			permCtl.load(this.options.resource);
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