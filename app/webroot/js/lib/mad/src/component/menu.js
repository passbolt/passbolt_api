import 'mad/component/component';
import 'mad/component/tree';
import 'mad/util/map/map';
import 'mad/model/action';
import 'mad/view/template/component/menu/menu_item.ejs!';

/**
 * @parent Mad.components_api
 * @inherits {mad.component.Tree}
 * @group mad.component.Menu.view_events 0 View Events
 *
 * The menu component is a simple implementation of a menu composed of a list of items.
 *
 * ## Example
 * @demo demo.html#menu/menu
 *
 * @constructor
 * Instantiate a new Menu Component.
 * @param {HTMLElement|can.NodeList|CSSSelectorString} el The element the control will be created on
 * @param {Object} [options] option values for the component.  These get added to
 * this.options and merged with defaults static variable
 *   * itemClass : class to be used for the items composing the menu
 *   * map : mapping object. (See mad.Map)
 * @return {mad.component.Menu}
 */
var Menu = mad.component.Menu = mad.component.Tree.extend('mad.component.Menu', {

    defaults: {
        label: 'Menu',
        cssClasses: ['menu'],
        // View class.
        viewClass: mad.view.component.Tree,
        // The template to use to render each action.
        itemTemplateUri: 'mad/view/template/component/menu/menu_item.ejs',
        // The class which represent the item.
        itemClass: mad.model.Action,
        // Mapping of the items for the view.
        map: new mad.Map({
            id: 'id',
            label: 'label',
            // @todo : be carefull, for now if no cssClasses defined while creating the action.
            // @todo : this mapping is not done, and the state is not added to css classes.
            cssClasses: {
                key: 'cssClasses',
                func: function (value, map, item, mappedValues) {
                    var mappedValue = $.merge([], value);
                    // If a state is defined for the given item.
                    // Add the state to the css classes.
                    if (typeof item.state != 'undefined') {
                        mappedValue = $.merge(mappedValue, item.state.current);
                    }
                    return mappedValue.join(' ');
                }
            },
            children: {
                key: 'children',
                func: mad.Map.mapObjects
            }
        })
    }

}, /** @prototype */ {

    /**
     * Set the item state.
     * @param id The item id.
     * @param stateName The state to set.
     */
    setItemState: function (id, stateName) {
        for (var i in this.options.items) {
            if (this.options.items[i].id == id) {
                this.options.items[i].state.setState(stateName);
                this.refreshItem(this.options.items[i]);
                return
            }
        }
        throw mad.Exception.get('The item [%0] is not an item of the menu', [id]);
    },

    /* ************************************************************** */
    /* LISTEN TO THE VIEW EVENTS */
    /* ************************************************************** */

    /**
     * An item has been selected
     * @parent mad.component.Menu.view_events
     * @param {HTMLElement} el The element the event occured on
     * @param {HTMLEvent} ev The event which occured
     * @param {string} item The selected item
     * @return {void}
     */
    ' item_selected': function (el, ev, item) {
        this._super(el, ev, item);

        // If this item is not disabled, try to execute the item action.
        if (!item.state.is('disabled')) {
            item.execute(this);
        }
    }
});

export default Menu;
