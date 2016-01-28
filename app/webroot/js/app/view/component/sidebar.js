import 'mad/view/component/tree';
import 'app/component/comments';
import 'app/component/sidebar_section';

/**
 * @inherits mad.View
 * @parent index
 *
 * @constructor
 * Creates a new Sidebar View
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.Sidebar}
 */
var Sidebar = passbolt.view.component.Sidebar = mad.View.extend('passbolt.view.component.Sidebar', /** @static */ {

}, /** @prototype */ {

    /**
     * Observe when the user clicks on the close button
     * @param {HTMLElement} el The element the event occurred on
     * @param {HTMLEvent} ev The event which occurred
     */
    ' .js_sidebar_close click': function(el, ev) {
        mad.Config.write('ui.workspace.showSidebar', false);
        mad.bus.trigger('workspace_sidebar_hide');
    }

});

export default Sidebar;