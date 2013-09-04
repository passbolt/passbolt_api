steal(
	'mad/controller/component/tabController.js',
	'app/controller/component/permissionsController.js'
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
	mad.controller.component.TabController.extend('passbolt.controller.component.ResourceActionsTabController', /** @static */ {

		'defaults': {
			'label': null,
			'resource': null,
			'cssClasses': ['tabs'],
			'viewClass': mad.view.component.Tab,
			'templateUri': 'mad/view/template/component/tab.ejs' // @todo Ouhhhh, dirty case, templateUri should be based on a weight system 1. Controller 2. View 3. auto
		}

	}, /** @prototype */ {
		
		// Constructor like
		'init': function(el, opts) {
			this._super(el, opts);
		},
		
		// after start
		'afterStart': function() {
			this._super();
			var self = this;
			
			// Instantiate the menu which will rule the tab container
			// this.menu = new mad.controller.component.MenuController($('.js_tabs_nav', this.element));
			// this.menu.start();
			// var menuItems = [
				// new mad.model.Action({ 'id': uuid(), 'label': __('edit'), 'action': function() { self.container.enableTab('js_resource_create'); } }),
				// new mad.model.Action({ 'id': uuid(), 'label': __('share'), 'action': function() { self.container.enableTab('js_permission'); } }),
				// new mad.model.Action({ 'id': uuid(), 'label': __('organize'), 'action': function() { self.container.enableTab('js_resource_create'); } }),
				// new mad.model.Action({ 'id': uuid(), 'label': __('logs'), 'action': function() { self.container.enableTab('js_resource_create'); } })
			// ];
			// this.menu.load(menuItems);
			
			// Instantiate tab container which will contain the resource's tools
			// this.container = new mad.controller.component.TabController($('.js_tabs_content', this.element));
			// this.container.start();

			// Add the edition form controller to the tab

			var editFormCtl = this.addComponent(passbolt.controller.form.resource.CreateFormController, {
				'id': 'js_rs_edit',
				'label': __('Edit'),
				'data': this.options.resource,
				'callbacks' : {
					'submit': function (data) {
						// save the resource's changes
						self.options.resource.attr(data['passbolt.model.Resource'])
							.save();
						// close the popup
						mad.app.getComponent('js_dialog')
							.remove();
					}
				}
			});
			
			// editFormCtl.start();
			this.enableTab('js_rs_edit');
			editFormCtl.load(this.options.resource);	

			// Add the permission controller to the tab
			var permCtl = this.addComponent(passbolt.controller.component.PermissionsController, {
				'id': 'js_permission',
				'label': 'Share',
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
			return;
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