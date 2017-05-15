import 'mad/view/view';

/**
 * @inherits mad.view.View
 */
var GroupSidebar = passbolt.view.component.GroupSidebar = passbolt.view.component.Sidebar.extend('passbolt.view.component.GroupSidebar', /** @static */ {

}, /** @prototype */ {

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * Observe when the user clicks on the edit button, or the description content, to modify the description content
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     */
    'a#js_edit_members_button click': function (el, ev) {
        ev.preventDefault();
        this.element.trigger('request_group_edition');
    }

});

export default GroupSidebar;
