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
        showSidebar: true,

        // Selected users or resources.
        selectedItems: new can.Model.List(),
	}

}, /** @prototype */ {

	/**
	 * after start hook.
	 * @return {void}
	 */
	afterStart: function () {
		// Manage the display of the sidebar
		var showSidebar = this.checkShowSidebar();
        mad.Config.write('ui.workspace.showSidebar', showSidebar);

		this.options.viewSidebarButton = new mad.component.ToggleButton($('#js_wk_secondary_menu_view_sidebar_button'), {
			state: showSidebar ? 'selected' : 'ready'
		}).start();

		// Rebind controller events
		this.on();
	},

    /**
     * Check the settings to see if the sidebar should be shown or hidden.
     * @return bool
     */
    checkShowSidebar: function() {
        var showSidebar = mad.Config.read('ui.workspace.showSidebar');
        if (showSidebar == undefined) {
            showSidebar = this.options.showSidebar;
        }
        return showSidebar;
    },

	/* ************************************************************** */
	/* LISTEN TO THE APP EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when another component wants the sidebar to be shown or hidden.
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @return {void}
	 */
	'{mad.bus.element} workspace_showSidebar': function (el, ev, show) {
        var sidebarShouldBeVisible = this.checkShowSidebar();

		if (!show && sidebarShouldBeVisible) {
			this.options.viewSidebarButton.setState('ready');
		}
	},

	/**
	 * Observe when the user wants to view the side bar
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @return {void}
	 */
	'{viewSidebarButton.element} click': function (el, ev) {
        // Status before click.
		var showSidebar = this.checkShowSidebar();
        console.log('selectedItems', this.options.selectedItems.length);
        var isSelection = this.options.selectedItems.length > 0;
        // New status for the sidebar (opposite to previous one).
        var showSidebarNewStatus = showSidebar ? false : true;
        console.log('showSidebar settings', showSidebarNewStatus);
        console.log('isSelection', isSelection);
        // Set new status in the settings.
		mad.Config.write('ui.workspace.showSidebar', showSidebarNewStatus);

        if ( isSelection ) {
            console.log('trigger sidebar', showSidebarNewStatus);
            // Trigger show sidebar event with the new status.
            mad.bus.trigger('workspace_showSidebar', showSidebarNewStatus);
        }
	}
});

export default WorkspaceSecondaryMenu;
