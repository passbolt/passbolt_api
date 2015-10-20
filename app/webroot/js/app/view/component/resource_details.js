import 'mad/view/view';

/**
 * @inherits mad.view.View
 */
var ResourceDetails = passbolt.view.component.ResourceDetails = mad.View.extend('passbolt.view.component.ResourceDetails', /** @static */ {

}, /** @prototype */ {

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user clicks on the close button
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @return {void}
	 */
	'.icon.close click': function(el, ev) {
		mad.Config.write('ui.workspace.showSidebar', false);
		mad.bus.trigger('workspace_showSidebar', false);
	},

	/**
	 * Observe when the user clicks on any h2 element, rolldown the following p tag
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @return {void}
	 */
	'h2 click': function (el, ev) {
		el.next('p').toggle();
	},

	/**
	 * Observe when the user clicks on any h2 element, rolldown the following p tag
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @return {void}
	 */
	'li.password .secret-copy > a click': function (el, ev) {
		ev.stopPropagation();
		ev.preventDefault();
		this.element.trigger('password_clicked', [ev]);
	}
});

export default ResourceDetails;