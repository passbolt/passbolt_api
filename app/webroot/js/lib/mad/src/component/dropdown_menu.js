import 'mad/component/menu';
import 'mad/view/component/dropdown_menu';
import 'mad/view/template/component/dropdown_menu/dropdown_menu.ejs!';

/**
 * @parent Mad.components_api
 * @inherits {mad.component.Menu}
 * @group mad.component.DropdownMenu.view_events 0 View Events
 *
 * The DropdownMenu component is an implementation of a dropdown menu.
 *
 * ## Example
 * @demo demo.html#dropdown_menu/dropdown_menu
 *
 * @constructor
 * Instantiate a new DropdownMenu Component.
 * @param {HTMLElement|can.NodeList|CSSSelectorString} el The element the control will be created on
 * @param {Object} [options] option values for the component.  These get added to
 * this.options and merged with defaults static variable
 *   * callbacks : callbacks that will be triggered on events :
 *      * item_selected : triggered when an item is selected.
 *      * item_right_selected : triggered when an item is right selected.
 *      * item_hovered : triggered when an item is hovered.
 * @return {mad.component.DropdownMenu}
 */
var DropdownMenu = mad.component.DropdownMenu = mad.component.Menu.extend('mad.component.DropdownMenu', {

    'defaults': {
        'label': 'Drop Down Menu Component',
        'viewClass': mad.view.component.DropdownMenu,
        'templateUri': 'mad/view/template/component/tree.ejs',
        'itemTemplateUri': 'mad/view/template/component/dropdown_menu/dropdown_menu.ejs',
        'cssClasses': ['dropdownmenu'],
        'callbacks': {
            'item_selected': null,
            'item_right_selected': null,
            'item_hovered': null
        }
    }

}, /** @prototype */ {

    /**
     * Open an item
     * @param {mad.model.Model} item The target item to open
     * @return {void}
     */
    open: function (item) {
        this.view.open(item);
    },

    /**
     * Close an item
     * @param {mad.model.Model} item The target item to close
     * @return {void}
     */
    close: function (item) {
        this.view.close(item);
    },

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * An item has been uncollapsed
     * @parent mad.component.DropdownMenu.view_events
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @param {mad.model.Model} item The target item
     * @return {void}
     */
    ' item_opened': function (el, ev, item) {
        this.open(item);
    },

    /**
     * An item has been uncollapsed
     * @parent mad.component.DropdownMenu.view_events
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @param {mad.model.Model} item The target item
     * @return {void}
     */
    ' item_closed': function (el, ev, item) {
        this.close(item);
    }
});

export default DropdownMenu;