import 'mad/view/view';
import 'app/view/component/sidebar_section';
import 'app/view/template/component/sidebar_section/permissions.ejs!';

/*
 * @inherits passbolt.view.component.SidebarSection
 */
var PermissionsView = passbolt.view.component.sidebarSection.Permissions = passbolt.view.component.SidebarSection.extend('passbolt.view.component.sidebarSection.Permissions', /** @static */ {

}, /** @prototype */ {

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user clicks on the edit button
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'a#js_edit_permissions_button click': function (el, ev) {
		if (this.getController().getViewData('administrable') !== false) {
			this.element.trigger('request_resource_permissions_edit');
		}
	}
});

export default PermissionsView;
