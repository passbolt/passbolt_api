steal(
	MAD_ROOT + '/controller/component/workspaceController.js',
	'app/controller/component/passwordBrowserController.js',
	'app/controller/component/categoryChooserController.js',
	'app/controller/component/resourceDetailsController.js',
	'app/controller/form/category/createFormController.js',
	'app/controller/form/resource/createFormController.js',
	'app/controller/component/userMenuButtonController.js'
).then(function ($) {

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
			'templateUri': '//app/view/template/passwordWorkspace.ejs'
		}

	}, /** @prototype */ {

		// constructor like
		'init': function (el, options) {
			this._super();
			this.render(); // render the component to be used by the others

			// *************************************************************
			// Header menu area
			// *************************************************************
			var newButton = new passbolt.controller.component.UserMenuButtonController($('#js_request_resource_creation_button', this.element));

			var editButton = new passbolt.controller.component.UserMenuButtonController($('#js_request_resource_edition_button', this.element), {
				'state': 'disabled'
			});

			var deleteButton = new passbolt.controller.component.UserMenuButtonController($('#js_request_resource_deletion_button', this.element), {
				'state': 'disabled'
			});

			var shareButton = new passbolt.controller.component.UserMenuButtonController($('#js_request_resource_sharing_button', this.element), {
				'state': 'disabled'
			});

			var moreButton = new passbolt.controller.component.UserMenuButtonController($('#js_request_resource_more_button', this.element), {
				'state': 'disabled'
			});

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
			resourceDetails.render();
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
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the user wants to create a new resource
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'#js_request_resource_creation_button click': function (element, event) {
			var categoryId = element.controller().getValue();
			passbolt.eventBus.trigger('request_resource_creation', categoryId);
		},

		/**
		 * Observe when the user wants to edit a resource
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'#js_request_resource_edition_button click': function (element, event) {
			var resourceId = element.controller().getValue();
			passbolt.eventBus.trigger('request_resource_edition', resourceId);
		},

		/**
		 * Observe when the user wants to delete a resource
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'#js_request_resource_deletion_button click': function (element, event) {
			var resourceId = element.controller().getValue();
			passbolt.eventBus.trigger('request_resource_deletion', resourceId);
		},

		/**
		 * Observe when the user wants to share a resource
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'#js_request_resource_sharing_button click': function (element, event, resourceId) {
			var resourceId = element.controller().getValue();
			passbolt.eventBus.trigger('request_resource_sharing', resourceId);
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when category is selected
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} category The selected Category
		 * @return {void}
		 */
		'{passbolt.eventBus} category_selected': function (element, evt, category) {
			mad.app.getComponent('js_request_resource_creation_button').setValue(category.id);
		},

		/**
		 * Observe when a resource is unselected
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} resourceId The unselected Resource id
		 * @return {void}
		 */
		'{passbolt.eventBus} resource_unselected': function (element, event, resourceId) {
			// The resource is no more selected, reinit the password workspace
			// component to its intitial state (ready)
			this.setState('ready');

			mad.app.getComponent('js_request_resource_edition_button').setState('disabled');
			mad.app.getComponent('js_request_resource_deletion_button').setState('disabled');
			mad.app.getComponent('js_request_resource_sharing_button').setState('disabled');
			mad.app.getComponent('js_request_resource_more_button').setState('disabled');
		},

		/**
		 * Observe when a resource is selected
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} resourceId The selected Resource id
		 * @return {void}
		 */
		'{passbolt.eventBus} resource_selected': function (element, event, resourceId) {
			// A resource has been selected, change the state of the password Workspace
			// controller
			this.setState('resourceSelected');
			mad.app.getComponent('js_request_resource_edition_button').setValue(resourceId).setState('ready');
			mad.app.getComponent('js_request_resource_deletion_button').setValue(resourceId).setState('ready');
			mad.app.getComponent('js_request_resource_sharing_button').setValue(resourceId).setState('ready');
			mad.app.getComponent('js_request_resource_more_button').setValue(resourceId).setState('ready');

			// Another way is to drive the state of all the component from here. I choose
			// for a first hit to lets the component manage their own states changement
			//				var secondSideBar = this.getApp().getComponent('js_passbolt_password_sidebar_second');
			//				secondSideBar.changeStatus('show');
		},

		/**
		 * Observe when the user want to copy the login to the clipboard
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} resourceId The resource id
		 * @return {void}
		 */
		'{passbolt.eventBus} copy_login_clipboard': function (element, evt, resourceId) {
			// @todo make the copy
			steal.dev.log('the password workspace listen to the event copy_login_clipboard');
		},

		/**
		 * Observe when the user want to copy the secret to the clipboard
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} resourceId The resource id
		 * @return {void}
		 */
		'{passbolt.eventBus} copy_secret_clipboard': function (element, evt, resourceId) {
			// @todo make the copy
			steal.dev.log('the password workspace listen to the event copy_secret_clipboard');
		},

		/**
		 * Observe when the user requests a category creation
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'{passbolt.eventBus} request_category_creation': function (element, evt, data) {
			var uid = uuid();
			var popup = mad.controller.component.PopupController.get({}, passbolt.controller.form.category.CreateFormController, {
				id: uid,
				data : { parentId: data.id },
				callbacks : {
					submit: function (data) {
						passbolt.controller.CategoryController.add(data);
						popup.goToHell();
					}
				}
			});
			mad.app.getComponent(uid).render();
		},

		/**
		 * Observe when the user requests a resource creation
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} categoryId The target category to insert the resource
		 * @return {void}
		 */
		'{passbolt.eventBus} request_resource_creation': function (element, evt, categoryId) {
			var uid = uuid();
			var popup = mad.controller.component.PopupController.get({
				label: __('Create a new Resource')
			}, passbolt.controller.form.resource.CreateFormController, {
				id: uid,
				data: { Category: { id: categoryId } },
				callbacks : {
					submit: function (data) {
						passbolt.controller.ResourceController.add(data);
						popup.goToHell();
					}
				}
			});
			mad.app.getComponent(uid).render();
		},

		/**
		 * Observe when the user requests a resource edition
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} resourceId The target resource to edit
		 * @return {void}
		 */
		'{passbolt.eventBus} request_resource_edition': function (element, evt, resourceId) {
			var uid = uuid();
			passbolt.model.Resource.get({
				id: resourceId
			}, function (request, response, resource) {
				
				var popup = mad.controller.component.PopupController.get({
					label: __('Edit a Resource')
				}, passbolt.controller.form.resource.CreateFormController, {
					id: uid,
					data : resource,
					callbacks : {
						submit: function (data) {
//							passbolt.controller.ResourceController.update(data);
							popup.goToHell();
						}
					}
				});
				mad.app.getComponent(uid).render();
				
			});
		},

		/**
		 * Observe when the user requests a resource deletion
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'{passbolt.eventBus} request_resource_deletion': function (element, evt, resourceId) {
			passbolt.controller.ResourceController.delete(resourceId);
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
				$('.js_workspace_main', this.element).removeClass('grid_7').addClass('grid_13 omega');
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
				$('.js_workspace_main', this.element).removeClass('grid_13 omega').addClass('grid_7');
				$('.js_workspace_sidebar_second', this.element).show();
			}
		}

	});

});