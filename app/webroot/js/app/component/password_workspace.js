import 'mad/component/component';
import 'app/component/password_workspace_menu';
//import 'app/component/password_breadcrumb';
//import 'app/component/category_actions_tab';
//import 'app/component/category_chooser';
import 'app/component/password_browser';
//import 'app/component/resource_actions_tab';
//import 'app/component/resource_details';
//import 'app/component/resource_shortcuts';
//import 'app/component/workspace_secondary_menu';
//import 'app/form/category/create';
//import 'app/form/resource/create';
import 'app/model/filter';

import 'app/view/template/password_workspace.ejs!';

/**
 * @inherits {mad.Component}
 * @parent index
 *
 * @constructor
 * Creates a new Password Workspace Controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.PasswordWorkspace}
 */
var PasswordWorkspace = passbolt.component.PasswordWorkspace = mad.Component.extend('passbolt.component.PasswordWorkspace', /** @static */ {

	defaults: {
		label: 'Password',
		templateUri: 'app/view/template/password_workspace.ejs',
		// The current selected resources
		selectedRs: new can.Model.List(),
		// The current filter
		filter: new passbolt.model.Filter()
	}

}, /** @prototype */ {

	/**
	 * Called right after the start function
	 * @return {void}
	 * @see {mad.Component}
	 */
	afterStart: function() {
		// Instantiate the primary workspace menu controller outside of the workspace container, destroy it when the workspace is destroyed
		var primWkMenu = mad.helper.Component.create(
			$('#js_wsp_primary_menu_wrapper'),
			'last',
			passbolt.component.PasswordWorkspaceMenu, {
				'selectedRs': this.options.selectedRs
			}
		);
		primWkMenu.start();

		//// Instantiate the secondary workspace menu controller outside of the workspace container, destroy it when the workspace is destroyed
		//var secWkMenu = mad.helper.Component.create(
		//	$('#js_wsp_secondary_menu_wrapper'),
		//	'last',
		//	passbolt.component.WorkspaceSecondaryMenu,
		//	{}
		//);
		//secWkMenu.start();
        //
		//// Instanciate the passwords filter controller
		//var rsShortcut = new passbolt.component.ResourceShortcuts('#js_wsp_pwd_filter_shortcuts', {});
		//rsShortcut.start();
        //
		//// Removed the lines below for #PASSBOLT-787
		////// Instanciate the categories chooser controller
		////this.catChooser = new passbolt.controller.component.CategoryChooserController('#js_wsp_pwd_category_chooser', {});
		////this.catChooser.start();
        //
		//// Instantiate the password workspace breadcrumb controller
		//this.breadcrumCtl = new passbolt.component.PasswordBreadcrumb($('#js_wsp_password_breadcrumb'), {});
		//this.breadcrumCtl.start();

		// Instanciate the passwords browser controller
		var passwordBrowserController = new passbolt.component.PasswordBrowser('#js_wsp_pwd_browser', {
			selectedRs: this.options.selectedRs
		});
		passwordBrowserController.start();

		//// Instanciate the resource details controller
		//var resourceDetails = new passbolt.component.ResourceDetails($('.js_wsp_pwd_sidebar_second', this.element), {
		//	'selectedRs': this.options.selectedRs
		//});

		// Filter the workspace.
		var filter = new passbolt.model.Filter({
			label: __('All items'),
			order: 'modified',
			type: passbolt.model.Filter.SHORTCUT
		});
		mad.bus.trigger('filter_resources_browser', filter);
	},

	/**
	 * Destroy the workspace.
	 */
	destroy: function() {
		// Be sure that the primary & secondary workspace menus controllers will be destroyed also.
		$('#js_wsp_primary_menu_wrapper').empty();
		$('#js_wsp_secondary_menu_wrapper').empty();

		this._super();
	},

	/**
	 * Demonstration function to prove the dispatcher
	 * @dev
	 */
	index: function (a, b, c) {
		console.log('Execute function index of the password workspace controller, with the following arguments');
		console.dir(arguments);
	},

	/* ************************************************************** */
	/* LISTEN TO THE APP EVENTS */
	/* ************************************************************** */

	///**
	// * Listen to the browser filter
	// * @param {jQuery} element The source element
	// * @param {Event} event The jQuery event
	// * @param {passbolt.model.Filter} filter The filter to apply
	// * @return {void}
	// */
	//'{mad.bus} filter_resources_browser': function (element, evt, filter) {
	//	// @todo fixed in future canJs.
	//	if (!this.element) return;
    //
	//	// Update the breadcrumb with the new filter.
	//	this.breadcrumCtl.load(filter);
	//},

	/**
	 * Observe when category is selected
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @param {passbolt.model.Category} category The selected category
	 * @return {void}
	 */
	'{mad.bus} category_selected': function (el, ev, category) {
		// @todo fixed in future canJs.
		if (!this.element) return;

		// reset the selected resources
		this.options.selectedRs.splice(0, this.options.selectedRs.length);
		// Set the new filter
		this.options.filter.attr({
			'foreignModels': {
				'Category': new can.List([category])
			},
			'type': passbolt.model.Filter.FOREIGN_MODEL
		});
		// propagate a special event on bus
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
		// @todo fixed in future canJs.
		if (!this.element) return;

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
		// @todo fixed in future canJs.
		if (!this.element) return;

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
		// @todo fixed in future canJs.
		if (!this.element) return;

		var category = new passbolt.model.Category({ parent_id: data.id });

		// get the dialog
		var dialog = new mad.component.Dialog(null, {
			label: __('Create a new Category'), cssClasses : ['dialog-wrapper']
			}).start();

		// attach the component to the dialog
		var form = dialog.add(passbolt.form.category.Create, {
			data: category,
			callbacks : {
				submit: function (data) {
					var instance = new passbolt.model.Category(data['passbolt.model.Category'])
						.save();
					dialog.remove();
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
		// @todo fixed in future canJs.
		if (!this.element) return;

		// get the dialog
		var dialog = new mad.component.Dialog(null, {label: __('Edit a Category')})
			.start();

		// Instanciate the Resource Actions Tab Controller into the dialog
		var tab = dialog.add(passbolt.component.CategoryActionsTab, {
			category: category
		});
		tab.enableTab('js_cat_edit');
	},

	/**
	 * Observe when the user requests a category sharing
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @return {void}
	 */
	'{mad.bus} request_category_sharing': function (el, ev, category) {
		// @todo fixed in future canJs.
		if (!this.element) return;

		// get the dialog
		var dialog = new mad.component.Dialog(null, {label: __('Share a Category')})
			.start();

		// Instanciate the Resource Actions Tab Controller into the dialog
		var tab = dialog.add(passbolt.component.CategoryActionsTab, {
			category: category
		});
		tab.enableTab('js_cat_permission');
	},

	/**
	 * Observe when the user requests a category deletion
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @return {void}
	 */
	'{mad.bus} request_category_deletion': function (el, ev, category) {
		// @todo fixed in future canJs.
		if (!this.element) return;

		category.destroy();
	},

	/**
	 * Observe when the user requests a resource creation
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @param {passbolt.model.Category} categories The target categories to insert the resource
	 * @return {void}
	 */
	'{mad.bus} request_resource_creation': function (el, ev, categories) {
		// @todo fixed in future canJs.
		if (!this.element) return;

		if(typeof categories == 'undefined') {
			categories = [];
		} else if (!$.isArray(categories)) {
			categories = [categories];
		}
		// create the resource which will be used by the form builder to populate the fields
		var resource = new passbolt.model.Resource({ Category: categories });

		// get the dialog
		var dialog = new mad.component.Dialog(null, {
			label: __('Create Password'), cssClasses : ['create-password-dialog','dialog-wrapper']
		}).start();

		// attach the component to the dialog
		var form = dialog.add(passbolt.form.resource.Create, {
			data: resource,
			callbacks : {
				submit: function (data) {
					var rs = new passbolt.model.Resource(data['passbolt.model.Resource']);
					rs.save();
					dialog.remove();
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
		// @todo fixed in future canJs.
		if (!this.element) return;

		// get the dialog
		var dialog = new mad.component.Dialog(null, {label: __('Edit Password')})
			.start();

		// Instanciate the Resource Actions Tab Controller into the dialog
		var tab = dialog.add(passbolt.component.ResourceActionsTab, {
			resource: resource
		});
		tab.enableTab('js_rs_edit');
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
		// @todo fixed in future canJs.
		if (!this.element) return;

		for (var i=2; i<arguments.length; i++) {
			var rs = arguments[i];
			if (!(rs instanceof passbolt.model.Resource)) {
				throw passbolt.Exception.get('The parameter [%0] should be an instance of passbolt.model.Resource', i);
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
		// @todo fixed in future canJs.
		if (!this.element) return;

		// get the dialog
		var dialog = new mad.component.Dialog(null, {label: __('Share Password')})
			.start();

		// Instanciate the Resource Actions Tab Controller into the dialog
		var tab = dialog.add(passbolt.component.ResourceActionsTab, {
			resource: resource
		});
		tab.enableTab('js_rs_permission');
	},

	/**
	 * Observe when the user requests to set an instance as favorite
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @param {jQuery.Deferred.Promise} promise The caller join a promise to complete, don't disapoint him !
	 * @param {passbolt.model.Model} instance The target instance to set as favorite
	 * @return {void}
	 */
	'{mad.bus} request_favorite': function (el, ev, promise, instance) {
		// @todo fixed in future canJs.
		if (!this.element) return;

		// Data expected to save a resource as favorite.
		var data = {
			'foreign_model': 'resource',
			'foreign_id': instance.id
		};

		// Save the given resource as favorite.
		new passbolt.model.Favorite(data)
			.save()
			.then(function(favorite){
				// Update the instance with the favorite data received from the back-end.
				instance.Favorite = favorite;
				// Notify can that the instance has been updated.
				// All subscribers will be notified about that change. By instance the password
				// browser (grid) will update the row of a resource.
				can.trigger(passbolt.model.Resource, 'updated', instance);
				// Notify the request caller by resolving the promise given in parameter of
				// the request.
				promise.resolve();
			})
			.fail(function(error){
				// Notify the request caller by rejecting the promise given in parameter of
				// the request.
				promise.reject();
			});
	},

	/**
	 * Observe when the user requests to unset an instance as favorite
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @param {jQuery.Deferred.Promise} promise The caller join a promise to complete, don't disapoint him !
	 * @param {passbolt.model.Model} instance The target instance to unset as favorite
	 * @return {void}
	 */
	'{mad.bus} request_unfavorite': function (el, ev, promise, instance) {
		// @todo fixed in future canJs.
		if (!this.element) return;

		// Unfavorite the given resource.
		instance.Favorite.destroy()
			.then(function() {
				// Update the resource.
				instance.Favorite = null;
				// Notify can that the instance has been updated.
				// All subscribers will be notified about that change. By instance the password
				// browser (grid) will update the row of a resource.
				can.trigger(passbolt.model.Resource, 'updated', instance);
				// Notify the request caller by resolving the promise given in parameter of
				// the request.
				promise.resolve();
			})
			.fail(function(jqXHR, status, response, request) {
				// Notify the request caller by rejecting the promise given in parameter of
				// the request.
				promise.rejectWith(promise, [jqXHR, status, response, request]);
			});
	}

});

export default PasswordWorkspace;
