steal(
	'mad/controller/component/workspaceController.js',
	'app/controller/component/passwordBrowserController.js',
	'app/controller/component/categoryChooserController.js',
	'app/controller/component/resourceDetailsController.js',
	'app/controller/component/passwordsActionsMenuController.js',
	'app/controller/form/category/createFormController.js',
	'app/controller/form/resource/createFormController.js',
	'app/controller/form/permission/grantFormController.js'
).then(function () {

	/*
	 * @class passbolt.controller.PasswordWorkspaceController
	 * @inherits {mad.controller.component.WorkspaceController}
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
	mad.controller.component.WorkspaceController.extend('passbolt.controller.PasswordWorkspaceController', /** @static */ {

		'defaults': {
			'label': 'Password',
			'templateUri': 'app/view/template/passwordWorkspace.ejs',
			'selectedRs': new can.Model.List()
		}

	}, /** @prototype */ {

		// constructor like
		'init': function (el, options) {
			this._super();
			this.render(); // render the component to be used by the others

			// *************************************************************
			// User menu area
			// *************************************************************
			var userMenu = this.addComponent(passbolt.controller.component.PasswordsActionsMenuController, {
				'id': 'js_passbolt_password_actions_menu',
				'selectedRs': this.options.selectedRs
			}, 'workspace_actions_container');
			userMenu.render();

			// *************************************************************
			// First side area
			// *************************************************************
			// Add the Category Chooser component
			var categoryChooser = this.addComponent(passbolt.controller.component.CategoryChooserController, {
				'id': 'js_passbolt_password_category_chooser'
			}, 'js_workspace_sidebar_first');
			categoryChooser.render();

			// *************************************************************
			// Main area
			// *************************************************************
			// Add the Password browser component
			var passwordBrowserController = this.addComponent(passbolt.controller.component.PasswordBrowserController, {
				'id': 'js_passbolt_password_browser',
				'selectedRs': this.options.selectedRs
			}, 'js_workspace_main');
			// passwordBrowserController = passwordBrowserController.decorate('mad.helper.component.BoxDecorator'); // decorator sample, oh yeah
			passwordBrowserController.render();

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
			// Set the new filter and propagate the information on bus
			var filter =  new passbolt.model.Filter({
				tags: [category],
				keywords: null
			});
			mad.bus.trigger('filter_resources_browser', filter);
		},

		/**
		 * Observe when a resource is selected and adapt the workspace view functions of
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The selected resource
		 * @return {void}
		 */
		'{selectedRs} add': function (el, ev, resource) {
			// if more than one resource selected, hide the right sidebar
			if (this.options.selectedRs.length > 1) {
				// this view interaction, but for now it will be like that
				$('.js_workspace_main', this.element).removeClass('middle').addClass('full');
				$('.js_workspace_sidebar_second', this.element).hide();
				
			// else if only 1 resource selected show the right sidebar
			} else {
				// this view interaction, but for now it will be like that
				$('.js_workspace_main', this.element).removeClass('full').addClass('middle');
				$('.js_workspace_sidebar_second', this.element).show();
			}
		},

		/**
		 * Observe when a resource is unselected and adapt the workspace view functions of
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The unselected resource
		 * @return {void}
		 */
		'{selectedRs} remove': function (el, ev, resource) {
			// if more just one resource selected, show the right sidebar
			if (this.options.selectedRs.length == 1) {
				// this view interaction, but for now it will be like that
				$('.js_workspace_main', this.element).removeClass('full').addClass('middle');
				$('.js_workspace_sidebar_second', this.element).show();
			
			// else if no resource selected or more than once, hide the right sidebar
			} else {
				// this view interaction, but for now it will be like that
				$('.js_workspace_main', this.element).removeClass('middle').addClass('full');
				$('.js_workspace_sidebar_second', this.element).hide();
			}
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
			var category = new passbolt.model.Category({
				parent_id: data.id
			});
			var popup = mad.controller.component.PopupController.getPopup({
				label: __('Create a new Category')
			}, passbolt.controller.form.category.CreateFormController, {
				data: category,
				callbacks : {
					submit: function (data) {
						var instance = new passbolt.model.Category(data['passbolt.model.Category'])
							.save();
						popup.remove();
					}
				}
			});
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
			var resource = new passbolt.model.Resource({
				Category: [{ id: category.id }]
			});
			var popup = mad.controller.component.PopupController.getPopup({
				label: __('Create a new Resource')
			}, passbolt.controller.form.resource.CreateFormController, {
				data: resource,
				callbacks : {
					submit: function (data) {
						new passbolt.model.Resource(data['passbolt.model.Resource'])
							.save();
						popup.remove();
					}
				}
			});
		},

		/**
		 * Observe when the user requests a resource edition
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The target resource to edit
		 * @return {void}
		 */
		'{mad.bus} request_resource_edition': function (el, ev, resource) {
			var popup = mad.controller.component.PopupController.getPopup({
				label: __('Edit a Resource')
			}, passbolt.controller.form.resource.CreateFormController, {
				data : resource,
				callbacks : {
					submit: function (data) {
						resource.attr(data['passbolt.model.Resource'])
							.save();
						popup.remove();
					}
				}
			});
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
			var popup = mad.controller.component.PopupController.getPopup({
				label: __('Share with people')
			}, passbolt.controller.form.permission.GrantFormController, {
				data : resource
				
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
		'stateReady': function (go) { },

	});

});