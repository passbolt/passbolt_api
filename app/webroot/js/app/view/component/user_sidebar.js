import 'mad/view/view';

/**
 * @inherits mad.view.View
 */
var UserSidebar = passbolt.view.component.UserSidebar = passbolt.view.component.Sidebar.extend('passbolt.view.component.UserSidebar', /** @static */ {

}, /** @prototype */ {

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user clicks on the copy key button.
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'a.copy-public-key click': function (el, ev) {
		ev.stopPropagation();
		ev.preventDefault();
		this.element.trigger('request_copy_publickey', [ev]);
	}
});

export default UserSidebar;
