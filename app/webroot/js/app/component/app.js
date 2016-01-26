import 'mad/component/component';
import 'app/util/common'
// the main workspaces of the application
import 'app/component/settings_workspace';
import 'app/component/password_workspace';
import 'app/component/people_workspace';
// common components of the application
import 'app/component/app_navigation_left';
import 'app/component/app_navigation_right';
import 'app/component/app_filter';
import 'app/component/profile_dropdown';
import 'app/component/notification';
import 'app/component/loading_bar';
// model
import 'app/model/user';
// the application template
import 'app/view/template/app.ejs!';

/**
* @inherits mad.controller.AppController
* @parent index
*
* The passbolt application controller.
*/
var App = passbolt.component.App = mad.Component.extend('passbolt.component.App', /** @static */ {

	defaults: {
        templateUri: 'app/view/template/app.ejs',
		// List of available workspaces.
		workspaces:[
			'password',
			'people',
			'settings'
		]
	}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * Initialize the application's components.
	 * @see {mad.Component}
	 */
	afterStart: function() {
		var self = this;
		this.workspace = null;

		// Instantiate the app navigation left controller
		var navLeftCtl = new passbolt.component.AppNavigationLeft($('#js_app_navigation_left'));
		navLeftCtl.start();

		// Instantiate the app navigation right controller
		var navRightCtl = new passbolt.component.AppNavigationRight($('#js_app_navigation_right'));
		navRightCtl.start();

		// Instantiate the filter controller
		var filterCtl = new passbolt.component.AppFilter($('#js_app_filter'), {});
		filterCtl.start();

		// Get logged in user.
		passbolt.model.User.findOne({
			id: mad.Config.read('user.id'),
			async: false
		}).then(function(user) {
			// Set current user.
			passbolt.model.User.setCurrent(user);
			// Instantiate the profile controller.
			self.profileDropDownCtl = new passbolt.component.ProfileDropdown($('#js_app_profile_dropdown'), {
				user: user
			});
			self.profileDropDownCtl.start();
		});

		// Instantiate the notification controller
		var notifCtl = new passbolt.component.Notification($('#js_app_notificator'), {});

		// Instantiate the laoding bar controller
		var loadingBarCtl = new passbolt.component.LoadingBar($('#js_app_loading_bar'), {
			'state': 'ready'
		});
		loadingBarCtl.start();
	},

	/* ************************************************************** */
	/* LISTEN TO THE APP EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user wants to switch to another workspace
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 * @param {string} workspace The target workspace
	 * @param {array} options Workspace's options
	 */
	'{mad.bus.element} workspace_selected': function (el, event, workspace, options) {
		options = typeof options != "undefined" ? options : {};

		// If workspace requested is same as current workspace, do nothing.
		// (it is already loaded).
		if (workspace == this.workspace) {
			return;
		}

		// Destroy the existing workspace and all its components.
		$('#js_app_panel_main').empty();

        // Remove any existing contextual menu.
        mad.component.ContextualMenu.remove();

		// Set class on top container.
		$('#container')
			.removeClass(this.options.workspaces.join(" "))
			.addClass(workspace);

		// Initialize the target workspace.
		var workspaceId = 'js_passbolt_' + workspace + '_workspace_controller',
			workspaceClass = passbolt.component[can.capitalize(workspace) + 'Workspace'],
			workspaceOptions = {
				id: workspaceId,
				label: workspace
			};

		// Extend default workspace options with the ones given in params.
		$.extend(workspaceOptions, options);

		// Instantiate the workspace component.
		var component = mad.helper.Component.create(
			$('#js_app_panel_main'),
			'last',
			workspaceClass,
			workspaceOptions
		);
		component.start();

		// Remember current workspace.
		this.workspace = workspace;
	},

	/**
	 * Observe when the user requests a dialog to be opened.
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 * @param {string} label The label of the dialog
	 * @param {array} options (optional) Options to give to the dialog controller
	 */
	'{mad.bus.element} request_dialog': function (el, ev, options) {
		var options = options || {};
		new mad.component.Dialog(null, options).start();
	},

	/**
	 * Remove all existing focus in the document.
	 *
	 * This way we can set the focus somewhere else in another iframe.
	 *
	 * @param el
	 * @param ev
	 * @param options
	 */
	'{mad.bus.element} remove_all_focuses': function (el, ev, options) {
		var $focused = $(':focus');
		$focused.blur();
	},

	/**
	 * Observe when the application processus have been all completed.
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'{mad.bus.element} passbolt_application_loading_completed': function (el, ev, options) {
		if(!$('html').hasClass('loaded')) {
			$('html')
				.removeClass('loading')
				.addClass('loaded');
		}
	},

	/**
	 * Observe when the user wants to close the latest dialog.
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'{mad.bus.element} passbolt_application_loading': function (el, ev, options) {
		if (!$('html').hasClass('loading')) {
			$('html')
				.removeClass('loaded')
				.addClass('loading');
		}
	},

	/**
	 * Observe when the user wants to close the latest dialog.
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'{mad.bus.element} request_dialog_close_latest': function (el, ev, options) {
		mad.component.Dialog.closeLatest();
	},


	/**
	 * The p3 narrow external lib caught a window resize event and
	 * set the appropriated classes to the body HTML Element.
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'{window} p3_narrow_checked': function(el, ev) {
		mad.bus.trigger('passbolt.html_helper.window_resized');
	},

	/* ************************************************************** */
	/* LISTEN TO THE STATE CHANGES */
	/* ************************************************************** */

	/**
	 * Listen to the change relative to the state Loading
	 * @param {boolean} go Enter or leave the state
	 */
	stateLoading: function (go) {
		// If the view has already been instanciated.
		// Notify it that the component is now loading.
		if (this.view) {
			this.view.loading(go);
		}
	},

	/**
	 * The application is ready.
	 * @param {boolean} go Enter or leave the state
	 */
	stateReady: function (go) {
		// Select the password workspace
		mad.bus.trigger('workspace_selected', 'password');
		//mad.bus.trigger('workspace_selected', 'people');
		// When the application is ready, remove the launching screen.
		$('html').removeClass('launching');
	}

});

export default App;
