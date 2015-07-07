steal(
	'mad/controller/component/tabController.js',
	'app/controller/component/permissionsController.js'
).then(function () {

	/*
	 * @class passbolt.controller.ResourceActionsTabController
	 * @inherits mad.controller.component.TabController
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

			// Add the edition form controller to the tab
			var editFormCtl = this.addComponent(passbolt.controller.form.resource.CreateFormController, {
				'id': 'js_rs_edit',
				'label': __('Edit'),
				'action': 'edit',
				'data': this.options.resource,
				'callbacks' : {
					'submit': function (data) {
						// save the resource's changes
						self.options.resource.attr(data['passbolt.model.Resource'])
							.save();
						// Close the dialog which contains this component.
						self.closest(mad.controller.component.DialogController)
							.remove();
					}
				}
			});
			editFormCtl.start();
			editFormCtl.load(this.options.resource);

			// Add the permission controller to the tab, if the user is allowed to share.
			var permCtl = this.addComponent(passbolt.controller.component.PermissionsController, {
				'id': 'js_rs_permission',
				'label': 'Share',
				'resource': this.options.resources,
				'cssClasses': ['share-tab']
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
		},

		/**
		 * Enable a tab
		 * @param {string} tabId id of the tab to enable
		 * @return {void}
		 */
		'enableTab': function (tabId) {
			this._super(tabId);
			// Change the label of the dialog which contains this component.
			var enabledTabCtl = this.getComponent(this.enabledTabId);
			var label = enabledTabCtl.options.label + '<span class="dialog-header-subtitle">' + this.options.resource.name + '</span>';
			this.closest(mad.controller.component.DialogController)
				.setTitle(label);
		}

	});

});