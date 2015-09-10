steal(
	'mad/controller/component/tabController.js',
	'app/controller/component/permissionsController.js'
).then(function () {

	/*
	 * @class passbolt.controller.CategoryActionsTabController
	 * @inherits mad.controller.component.TabController
	 * @parent index 
	 * 
	 * @constructor
	 * Creates new CategoryActionsTabController
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.CategoryActionsTabController}
	 */
	mad.controller.component.TabController.extend('passbolt.controller.component.CategoryActionsTabController', /** @static */ {

		'defaults': {
			'label': null,
			'category': null,
			'cssClasses': ['tabs'],
			// @todo The system is trying to get the view class of the ResourceActionsTabController.
			// @todo But we want this component to be based on the parent viewClass & templateUri.
			// @todo It's maybe a todo if we want to automatize this case.
			'viewClass': mad.view.component.Tab,
			'templateUri': 'mad/view/template/component/tab.ejs'
		}

	}, /** @prototype */ {

		// after start
		'afterStart': function() {
			this._super();
			var self = this;

			// Is the resource editable ?
			var canUpdate = passbolt.model.Permission.isAllowedTo(this.options.category, passbolt.UPDATE);
			// Is the resource administrable ?
			var canAdmin = passbolt.model.Permission.isAllowedTo(this.options.category, passbolt.ADMIN);

			// Add the edition form controller to the tab
			if (canUpdate) {
				var editFormCtl = this.addComponent(passbolt.controller.form.category.CreateFormController, {
					'id': 'js_cat_edit',
					'label': __('Edit'),
					'data': self.options.category,
					'callbacks' : {
						'submit': function (data) {
							self.options.category.attr(data['passbolt.model.Category'])
								.save();
							// Close the dialog which contains this component.
							self.closest(mad.controller.component.DialogController)
								.remove();
						}
					}
				});
				editFormCtl.start();
				editFormCtl.load(this.options.category);
			}

			// Add the permission controller to the tab
			if (canAdmin) {
				var permCtl = this.addComponent(passbolt.controller.component.PermissionsController, {
					'id': 'js_cat_permission',
					'label': 'Share',
					'resource': this.options.category,
					'cssClasses': ['share-tab']
				});
				permCtl.start();
				permCtl.load(this.options.category);
			}
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
		},

		/**
		 * Enable a tab
		 * @param {string} tabId id of the tab to enable
		 * @return {void}
		 */
		'enableTab': function (tabId) {
			this._super(tabId);
			// change the popup dialog
			var enabledTabCtl = this.getComponent(this.enabledTabId);
			var label = enabledTabCtl.options.label + '<span class="dialog-header-subtitle">' + this.options.category.name + '</span>';
			mad.app.getComponent('js_dialog')
				.setTitle(label);
		}
		 
	});

});