import 'mad/component/component';
import 'mad/component/toggle_button';
import 'app/view/template/component/workspace_secondary_menu.ejs!';

/**
 * @inherits mad.Component
 * @parent index
 *
 * Our passbolt workspace secondary menu controller
 *
 * @constructor
 * Creates a new workspace secondary menu controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller. These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.WorkspaceSecondaryMenu}
 */
var WorkspaceSecondaryMenu = passbolt.component.WorkspaceSecondaryMenu = mad.Component.extend('passbolt.component.WorkspaceSecondaryMenu', /** @static */ {

	defaults: {
		label: 'Workspace Secondary Menu',
		templateUri: 'app/view/template/component/workspace_secondary_menu.ejs',
		tag: 'ul',

        // Selected users or resources.
        selectedItems: new can.Model.List()
	}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
	afterStart: function () {
		// Manage the display of the sidebar
		var showSidebar = mad.Config.read('ui.workspace.showSidebar');

		this.options.viewSidebarButton = new mad.component.ToggleButton($('#js_wk_secondary_menu_view_sidebar_button'), {
			state: showSidebar ? 'selected' : 'ready'
		}).start();

		// Rebind controller events
		this.on();
	},

	/* ************************************************************** */
	/* LISTEN TO THE APP EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when sidebar is close by another component.
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'{mad.bus.element} workspace_sidebar_hide': function (el, ev) {
		if (this.options.viewSidebarButton.state.is('selected')) {
			this.options.viewSidebarButton.setState('ready');
		}
	},

	/**
	 * Observe when the user wants to view the side bar
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'{viewSidebarButton.element} click': function (el, ev) {
		var showSidebar = !mad.Config.read('ui.workspace.showSidebar'),
			isSelection = this.options.selectedItems.length > 0;

		// Set new status in the settings.
		mad.Config.write('ui.workspace.showSidebar', showSidebar);

		if (isSelection) {
			// Trigger show sidebar event with the new status.
			if (showSidebar) {
				mad.bus.trigger('workspace_sidebar_show');
			} else {
				mad.bus.trigger('workspace_sidebar_hide');
			}
		}
	}
});

export default WorkspaceSecondaryMenu;
