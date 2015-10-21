import 'mad/util/map/map';
import 'mad/component/tree';
import 'mad/view/component/dynamic_tree';
import 'mad/view/template/component/tree/tree.ejs!';
import 'mad/view/template/component/dynamic_tree/dynamic_tree.ejs!';


/**
 * @parent Mad.components_api
 * @inherits mad.component.Tree
 * @group mad.component.DynamicTree.view_events 0 View Events
 *
 * Implementation of a dynamic Tree component where it is possible to
 * browse the tree by clicking on its items.
 *
 * ## Example
 * @demo demo.html#dynamic_tree/dynamic_tree
 *
 * @constructor
 * Create a new Dynamic Tree.
 * @signature `new mad.Component.DynamicTree( element, options )`
 * @param {HTMLElement|can.NodeList|CSSSelectorString} el The element the control will be created on
 * @param {Object} [options] option values for the component.  These get added to
 * this.options and merged with defaults static variable
 * @return {mad.component.DynamicTree}
 */
var DynamicTree = mad.component.DynamicTree = mad.component.Tree.extend('mad.component.DynamicTree', {

    defaults: {
        label: 'Dynamic Tree Component',
        viewClass: mad.view.component.DynamicTree,
        templateUri: 'mad/view/template/component/tree/tree.ejs',
        itemTemplateUri: 'mad/view/template/component/dynamic_tree/dynamic_tree.ejs',
        map: null,
        callbacks: {
            item_selected: null,
            item_right_selected: null,
            item_hovered: null
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
     * @function mad.component.DynamicTree.item_opened
     * @parent mad.component.DynamicTree.view_events
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @param {mad.model.Model} item The target item
     * @return {void}
     */
    ' item_opened': function (el, ev, item) {
        this.open(item);
    },

    /**
     * An item has been collapsed
     * @function mad.component.DynamicTree.item_closed
     * @parent mad.component.DynamicTree.view_events
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @param {mad.model.Model} item The target item
     * @return {void}
     */
    ' item_closed': function (el, ev, item) {
        this.close(item);
    }

});

export default DynamicTree;