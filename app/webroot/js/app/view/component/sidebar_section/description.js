import 'mad/view/view';
import 'app/view/component/sidebar_section';
import 'app/view/template/component/sidebar_section/description.ejs!';

/*
 * @inherits passbolt.view.component.SidebarSection
 */
var Description = passbolt.view.component.sidebarSection.Description = passbolt.view.component.SidebarSection.extend('passbolt.view.component.sidebarSection.Description', /** @static */ {

}, /** @prototype */ {

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user clicks on the edit button, or the description content, to modify the description content
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @return {void}
	 */
	'a#js_edit_description_button, p.description_content click': function (el, ev) {
		this.element.trigger('request_resource_description_edit');
	},

	/**
	 * Set the visibility of the description
	 *
	 * @param {boolean} visible Whether it is visible
	 */
	showDescription: function(visible) {
		if(visible) {
			$('.description_content', $(this.element)).show();
		}
		else {
			$('.description_content', $(this.element)).hide();
		}
	}
});
export default Description;
