steal(
	'mad/controller/component/freeCompositeController.js',
	'app/controller/component/passwordBrowserController.js',
	'app/controller/component/categoryChooserController.js',
	'app/controller/component/resourceDetailsController.js',
	'app/controller/component/resourceActionsTabController.js',
	'app/controller/component/workspaceMenuController.js',
	'app/controller/component/workspaceSecondaryMenuController.js',
	'app/controller/form/category/createFormController.js',
	'app/controller/form/resource/createFormController.js',
	'app/model/filter.js'
).then(function () {

	/*
	 * @class passbolt.controller.PasswordWorkspaceController
	 * @inherits {mad.controller.component.ComponentController}
	 * @parent index 
	 * 
	 * @constructor
	 * Creates a new Password Workspace Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.PasswordWorkspaceController}
	 */
	mad.controller.component.FreeCompositeController.extend('passbolt.controller.PasswordWorkspaceController', /** @static */ {

		'defaults': {
			'label': 'Password',
			'templateUri': 'app/view/template/passwordWorkspace.ejs',
			'selectedRs': new can.Model.List(),
			'filter': new passbolt.model.Filter()
		}

	}, /** @prototype */ {

		'afterStart': function() {

			// Instantiate the primary workspace menu controller
			this.primMenu = new passbolt.controller.component.WorkspaceMenuController('#js_wsp_primary_menu', {
				'selectedRs': this.options.selectedRs
			});
			this.primMenu.start();

			// Instantiate the secondary workspace menu controller
			this.secMenu = new passbolt.controller.component.WorkspaceSecondaryMenuController('#js_wsp_secondary_menu', {});
			this.secMenu.start();
			
			// Instanciate the categories chooser controller
			this.catChooser = new passbolt.controller.component.CategoryChooserController('#js_wsp_pwd_category_chooser', {});
			this.catChooser.start();

			// Instanciate the passwords browser controller
			var passwordBrowserController = this.addComponent(passbolt.controller.component.PasswordBrowserController, {
				'id': 'js_passbolt_password_browser',
				'selectedRs': this.options.selectedRs
			}, 'js_workspace_main');
			passwordBrowserController.start();

			// *************************************************************
			// Second side area - create a container to be able to add other tool after
			// *************************************************************

			// Add vertical container to the second side area
			var resourceDetails = new passbolt.controller.component.ResourceDetailsController($('.js_workspace_sidebar_second', this.element), {
				'id': 'js_passbolt_password_sidebar_second',
				'selectedRs': this.options.selectedRs,
				'readyState': 'hidden'
			});
		},
		
		/**
		 * Demonstration function to prove the dispatcher
		 * @dev
		 */
		'index': function (a, b, c) {
			console.log('Execute function index of the password workspace controller, with the following arguments');
			console.dir(arguments);
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when category is selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Category} category The selected category
		 * @return {void}
		 */
		'{mad.bus} category_selected': function (el, ev, category) {
			// reset the selected resources
			this.options.selectedRs.splice(0, this.options.selectedRs.length);
			// Set the new filter
			this.options.filter.attr({
				'tags': [category],
				'keywords': null
			});
			// propagate a special event on bus
			// @todo not the best way to do ! call an event like that is like calling a function directly, useless
			mad.bus.trigger('filter_resources_browser', this.options.filter);
		},

		/**
		 * Observe when the user want to copy the login to the clipboard
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The selected resource
		 * @return {void}
		 */
		'{mad.bus} copy_login_clipboard': function (el, ev, resource) {
			// @todo make the copy
			steal.dev.log('the password workspace listen to the event copy_login_clipboard');
		},

		/**
		 * Observe when the user want to copy the secret to the clipboard
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The selected resource
		 * @return {void}
		 */
		'{mad.bus} copy_secret_clipboard': function (el, ev, resource) {
			// @todo make the copy
			steal.dev.log('the password workspace listen to the event copy_secret_clipboard');
		},

		/**
		 * Observe when the user requests a category creation
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{mad.bus} request_category_creation': function (el, ev, data) {
			var category = new passbolt.model.Category({ parent_id: data.id });
			
			// get the popup
			var popup = new mad.controller.component.DialogController({label: __('Create a new Category')})
				.start();
			
			// attach the component to the popup
			var form = popup.add(passbolt.controller.form.category.CreateFormController, {
				data: category,
				callbacks : {
					submit: function (data) {
						var instance = new passbolt.model.Category(data['passbolt.model.Category'])
							.save();
						popup.remove();
					}
				}
			});
			
			form.load(category);
		},

		/**
		 * Observe when the user requests a category edition
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{mad.bus} request_category_edition': function (el, ev, category) {
			
			// get the popup
			var popup = new mad.controller.component.DialogController({label: __('Edit a Category')})
				.start();
			
			// attach the component to the popup
			var form = popup.add(passbolt.controller.form.category.CreateFormController, {
				data: category,
				callbacks : {
					submit: function (data) {
						category.attr(data['passbolt.model.Category'])
							.save();
						popup.remove();
					}
				}
			});
			
			form.load(category);
		},

		/**
		 * Observe when the user requests a category deletion
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{mad.bus} request_category_deletion': function (el, ev, category) {
			category.destroy();
		},

		/**
		 * Observe when the user requests a resource creation
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Category} category The target category to insert the resource
		 * @return {void}
		 */
		'{mad.bus} request_resource_creation': function (el, ev, category) {
			// get the category from the filter
			var categories = [];
			this.options.filter.tags.each(function(val, i){
				categories.push({
					'id': val.id
				});
			});
			// create the resource which will be used by the form builder to populate the fields
			var resource = new passbolt.model.Resource({ Category: categories });
			
			// get the popup
			var popup = new mad.controller.component.DialogController(null, {label: __('Create a new Resource')});
			popup.start();
				// .start();
			
			// attach the component to the popup
			var form = popup.add(passbolt.controller.form.resource.CreateFormController, {
				data: resource,
				callbacks : {
					submit: function (data) {
						var rs = new passbolt.model.Resource(data['passbolt.model.Resource']);
						rs.save();
						popup.remove();
					}
				}
			});
			form.load(resource);
		},

		/**
		 * Observe when the user requests a resource edition
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The target resource to edit
		 * @return {void}
		 */
		'{mad.bus} request_resource_edition': function (el, ev, resource) {
			// get the popup
			var popup = new mad.controller.component.DialogController({label: __('Edit a Resource')})
				.start();
			
			// attach the component to the popup
			var form = popup.add(passbolt.controller.form.resource.CreateFormController, {
				data: resource,
				callbacks : {
					submit: function (data) {
						resource.attr(data['passbolt.model.Resource'])
							.save();
						popup.remove();
					}
				}
			});
			form.load(resource);
		},

		/**
		 * Observe when the user requests a resource deletion
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} rs1 A target resource to delete
		 * @param {passbolt.model.Resource} [rs2 ...] Other resources to delete
		 * @return {void}
		 */
		'{mad.bus} request_resource_deletion': function (el, ev) {
			for (var i=2; i<arguments.length; i++) {
				var rs = arguments[i];
				if (!(rs instanceof passbolt.model.Resource)) {
					throw new mad.error.Exception('The parameter ' + i + ' should be an instance of passbolt.model.Resource');
				}
				rs.destroy();
			}
		},
		
		/**
		 * Observe when the user requests a resource deletion
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} rs1 A target resource to delete
		 * @param {passbolt.model.Resource} [rs2 ...] Other resources to delete
		 * @return {void}
		 */
		'{mad.bus} request_resource_sharing': function (el, ev, resource) {
			// get the popup
			var dialog = new mad.controller.component.DialogController({label: ''})
				.start();

			// Instanciate the Resource Actions Tab Controller into the popup
			dialog.add(passbolt.controller.component.ResourceActionsTabController, {
				resource: resource
			});
		},

		/**
		 * Observe when the user requests to set an instance as favorite
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Model} instance The target instance to set as favorite
		 * @return {void}
		 */
		'{mad.bus} request_favorite': function (el, ev, instance) {
			// gather the data to create a new favorite
			var data = {
				'foreign_model': 'resource',
				'foreign_id': instance.id
			};
			
			// create a new permission
			new passbolt.model.Favorite(data)
				.save(function(favorite){
					instance.Favorite = favorite;
					can.trigger(passbolt.model.Resource, 'updated', instance);
				});
		},

		/**
		 * Observe when the user requests to unset an instance as favorite
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Model} instance The target instance to unset as favorite
		 * @return {void}
		 */
		'{mad.bus} request_unfavorite': function (el, ev, instance) {
			console.log(instance.Favorite);
			instance.Favorite.destroy(function() {
				instance.Favorite = null;
				can.trigger(passbolt.model.Resource, 'updated', instance);
			});
		},

		/* ************************************************************** */
		/* LISTEN TO THE STATE CHANGES */
		/* ************************************************************** */

		/**
		 * Listen to the change relative to the state Ready.
		 * The ready state is fired automatically after the Component is rendered
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateReady': function (go) { }

	});

});