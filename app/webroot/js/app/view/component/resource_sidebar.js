import 'app/view/component/sidebar';

/**
 * @inherits mad.view.View
 */
var ResourceSidebar = passbolt.view.component.ResourceSidebar = passbolt.view.component.Sidebar.extend('passbolt.view.component.ResourceSidebar', /** @static */ {

}, /** @prototype */ {

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user clicks on any h2 element, rolldown the following p tag
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'h2 click': function (el, ev) {
		el.next('p').toggle();
	},

	/**
	 * Observe when the user clicks on any h2 element, rolldown the following p tag
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'li.password .secret-copy > a click': function (el, ev) {
		ev.stopPropagation();
		ev.preventDefault();
		this.element.trigger('password_clicked', [ev]);
	}
});

export default ResourceSidebar;
