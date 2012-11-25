steal(
	'mad/controller/component/workspaceController.js',
	'app/controller/component/passwordBrowserController.js',
	'app/controller/component/categoryChooserController.js',
	'app/controller/component/resourceDetailsController.js',
	'app/controller/component/passwordsActionsMenuController.js',
	'app/controller/form/category/createFormController.js',
	'app/controller/form/resource/createFormController.js'
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
			'templateUri': 'app/view/template/passwordWorkspace.ejs'
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
				'id': 'js_passbolt_password_actions_menu'
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
				'id': 'js_passbolt_password_browser'
			}, 'js_workspace_main');
			// passwordBrowserController = passwordBrowserController.decorate('mad.helper.component.BoxDecorator'); // decorator sample, oh yeah
			passwordBrowserController.render();

			// *************************************************************
			// Second side area - create a container to be able to add other tool after
			// *************************************************************

			// Add vertical container to the second side area
			var resourceDetails = new passbolt.controller.component.ResourceDetailsController($('.js_workspace_sidebar_second', this.element), {
				'id': 'js_passbolt_password_sidebar_second',
				'readyState': 'hidden'
			});
//			resourceDetails.render();
		},

		/**
		 * Demonstration function to prove the dispatcher
		 * @dev
		 */
		'index': function (a, b, c) {
//			console.log('Execute function index of the password workspace controller, with the following arguments');
//			console.dir(arguments);
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
		'{passbolt.eventBus} category_selected': function (el, ev, category) {
			var filter =  new passbolt.model.Filter({
				categories: [category],
				keywords: null
			});
			console.log(filter);
			passbolt.eventBus.trigger('filter_resources_browser', filter);
		},

		/**
		 * Observe when a resource is unselected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The unselected resource
		 * @return {void}
		 */
		'{passbolt.eventBus} resource_unselected': function (el, ev, resource) {
			// The resource is no more selected, reinit the password workspace
			// component to its intitial state (ready)
			this.setState('ready');
		},

		/**
		 * Observe when a resource is selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The selected resource
		 * @return {void}
		 */
		'{passbolt.eventBus} resource_selected': function (el, ev, resource) {
			// A resource has been selected, change the state of the password Workspace
			// controller
			this.setState('resourceSelected');
		},

		/**
		 * Observe when the user want to copy the login to the clipboard
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Resource} resource The selected resource
		 * @return {void}
		 */
		'{passbolt.eventBus} copy_login_clipboard': function (el, ev, resource) {
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
		'{passbolt.eventBus} copy_secret_clipboard': function (el, ev, resource) {
			// @todo make the copy
			steal.dev.log('the password workspace listen to the event copy_secret_clipboard');
		},

		/**
		 * Observe when the user requests a category creation
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{passbolt.eventBus} request_category_creation': function (el, ev, data) {
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
		'{passbolt.eventBus} request_category_deletion': function (el, ev, category) {
			category.destroy();
		},

		/**
		 * Observe when the user requests a resource creation
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Category} category The target category to insert the resource
		 * @return {void}
		 */
		'{passbolt.eventBus} request_resource_creation': function (el, ev, category) {
			var resource = new passbolt.model.Resource({
				Category: [{ id: category.id }]
			});
			var popup = mad.controller.component.PopupController.getPopup({
				label: __('Create a new Resource')
			}, passbolt.controller.form.resource.CreateFormController, {
				data: resource,
				callbacks : {
					submit: function (data) {
						var instance = new passbolt.model.Resource(data['passbolt.model.Resource'])
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
		'{passbolt.eventBus} request_resource_edition': function (el, ev, resource) {
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
		 * @param {passbolt.model.Resource} resource The target resource to delete
		 * @return {void}
		 */
		'{passbolt.eventBus} request_resource_deletion': function (el, ev, resource) {
			resource.destroy();
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
		'stateReady': function (go) {
			if (go) {
				$('.js_workspace_main', this.element).removeClass('middle').addClass('right');
				$('.js_workspace_sidebar_second', this.element).hide();
			}
		},

		/**
		 * Listen to the change relative to the state ResourceSelected
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateResourceSelected': function (go) {
			if (go) {
				$('.js_workspace_main', this.element).removeClass('right').addClass('middle');
				$('.js_workspace_sidebar_second', this.element).show();
			}
		}

	});

});