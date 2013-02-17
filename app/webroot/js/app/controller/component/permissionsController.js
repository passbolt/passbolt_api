steal(
	'app/view/component/permissions.js'
).then(function () {

	/*
	 * @class passbolt.controller.PermissionsController
	 * @inherits mad.controller.component.ComponentController
	 * @parent index 
	 * 
	 * @constructor
	 * Creates a new Permissions Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.PermissionsController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.PermissionsController', /** @static */ {

		'defaults': {
			'label': 'Permissions Controller',
			'viewClass': passbolt.view.component.Permissions,
			// the resource to bind the component on
			'resource': null,
			// the selected resources, you can pass an existing list as parameter of the constructor to share the same list
			//'selectedRs': new can.Model.List()
		}

	}, /** @prototype */ {
		
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
		},
	});

});