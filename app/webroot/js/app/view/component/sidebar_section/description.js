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
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	'a#js_edit_description_button, p.description_content click': function (el, ev) {
        if (this.getController().getViewData('editable') !== false) {
		    this.element.trigger('request_resource_description_edit');
        }
	},

    /**
     * Observe when a click is done anywhere in the window.
     * If a click is done while being in edit mode, we cancel the edit and back to normal state.
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
     */
    '{window} click': function(el, ev) {
        // Are we in edit state.
        var isEditState = this.getController().state.is('edit');
        // Source of the click.
        var evtSrc = ev.originalEvent.target;
        // Description p element.
        var descriptionElt = $('p.description_content', this.getController().element).get(0);
        // Edit button element.
        var editButtonElement = $('a#js_edit_description_button i', this.getController().element).get(0);
        // Is the click providing from an element that triggers edit ?
        var clickIsOnEditElement = descriptionElt == evtSrc || editButtonElement == evtSrc;

        // If we are in edit mode, and the click doesn't come from the element containing the description.
        if (isEditState && ! clickIsOnEditElement) {
            // We intercept the click only if it's outside of the form.
            var $form = $('.form-content', this.getController().element);
            var contained = $.contains($form.get(0), evtSrc);
            if (!contained) {
                this.getController().setState('ready');
            }
        }
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
