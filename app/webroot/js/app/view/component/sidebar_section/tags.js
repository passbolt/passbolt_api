import 'mad/view/view';
import 'app/view/component/sidebar_section';
import 'app/view/template/component/sidebar_section/tags.ejs!';

/*
 * @inherits passbolt.view.component.SidebarSection
 */
var Tags = passbolt.view.component.sidebarSection.Tags = mad.View.extend('passbolt.view.component.sidebarSection.Tags', /** @static */ {

}, /** @prototype */ {

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user clicks on the plus button, to add a tag
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 * @return {void}
	 */
	'a.edit-action click': function (el, ev) {
		this.element.trigger('request_tags_edit');
	}

});

export default Tags;