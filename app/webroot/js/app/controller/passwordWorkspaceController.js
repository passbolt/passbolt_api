steal(MAD_ROOT + '/controller/component/workspaceController.js', 
	'app/controller/component/passwordBrowserController.js', 
	'app/controller/component/categoryChooserController.js', 
	'app/controller/component/resourceDetailsController.js')

.then(function ($) {

	/*
	 * @class passbolt.controller.PasswordWorkspaceController
	 * @inherits {mad.controller.component.WorkspaceController}
	 * @parent index 
	 * 
	 * @constructor
	 * Creates a new PasswordWorkspaceController
	 * @return {passbolt.controller.PasswordWorkspaceController}
	 */
	mad.controller.component.WorkspaceController.extend('passbolt.controller.PasswordWorkspaceController',
	/** @static */
	{
		'defaults': {
			'label': 'Password',
			'templateUri': '//app/view/template/passwordWorkspace.ejs'
		}
	},
	/** @prototype */
	{
		// constructor like
		'init': function (el, options) {
			this._super();
			this.render(); // render the component to be used by the others

			// *************************************************************
			// Header menu area
			// *************************************************************
			var newButton = new mad.controller.component.ButtonController($('#js_new_resource_button', this.element));
			
			var editButton = new mad.controller.component.ButtonController($('#js_edit_resource_button', this.element), {
				'state': 'disabled'
			});
			
			var shareButton = new mad.controller.component.ButtonController($('#js_share_resource_button', this.element), {
				'state': 'disabled'
			});
			
			var moreButton = new mad.controller.component.ButtonController($('#js_more_resource_button', this.element), {
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
			passwordBrowserController = passwordBrowserController.decorate('mad.helper.component.BoxDecorator'); // decorator sample, oh yeah
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
//			var secondSideContainer = new mad.controller.component.ContainerController($('.js_workspace_sidebar_second', this.element), {
//				'id': 'js_passbolt_password_sidebar_second',
//				'templateUri': '//' + MAD_ROOT + '/view/template/component/container/vertical.ejs',
//				'readyState': 'hidden'
//			});
//			secondSideContainer.render();
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
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the user wants to create a new resource
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'#js_new_resource_button click': function (element, event) {
			
		},

		/**
		 * Observe when the user wants to edit a resource
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} resourceId The resource id of the resource to edit
		 * @return {void}
		 */
		'#js_edit_resource_button click': function (element, event, resourceId) {
			mad.controller.component.PopupController.get({
				'label': 'edit a resource'
			});
		},

		/**
		 * Observe when the user wants to share a resource
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {string} resourceId The resource id of the resource to share
		 * @return {void}
		 */
		'#js_share_resource_button click': function (element, event, resourceId) {
			mad.controller.component.PopupController.get({
				'label': 'share a resource'
			});
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

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
			
			this.getComponent('js_edit_resource_button').setValue(resourceId).setState('disabled');
			this.getComponent('js_share_resource_button').setValue(resourceId).setState('disabled');
			this.getComponent('js_more_resource_button').setValue(resourceId).setState('disabled');
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
			
			this.getComponent('js_edit_resource_button').setState('ready');
			this.getComponent('js_share_resource_button').setState('ready');
			this.getComponent('js_more_resource_button').setState('ready');
			
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
			} else {
				//
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
			} else {
				//
			}
		}

	});

});