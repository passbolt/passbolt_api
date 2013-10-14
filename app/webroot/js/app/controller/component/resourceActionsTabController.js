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
			editFormCtl.start();
			editFormCtl.load(this.options.resource);

			// Add the permission controller to the tab
			var permCtl = this.addComponent(passbolt.controller.component.PermissionsController, {
				'id': 'js_rs_permission',
				'label': 'Share',
				'resource': this.options.resources,
				'cssClasses': ['form-content']
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