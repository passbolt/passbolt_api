import 'mad/component/button';
import 'mad/component/menu';
import 'mad/view/component/button_dropdown';
import 'mad/view/template/component/button_dropdown/button_dropdown.ejs!';

/**
 * @parent Mad.components_api
 * @inherits mad.component.Button
 * @group mad.component.ButtonDropdown.state_changes 0 State Changes
 *
 * The Button Dropdown class Controller is our implementation of the UI component Drop down button.
 *
 * ## Example
 * @demo demo.html#button_dropdown/button_dropdown
 *
 * @constructor
 * Creates a new Button Drop down Component
 * @signature `new mad.Component.ButtonDropdown( element, options )`
 * @param {HTMLElement|can.NodeList|CSSSelectorString} el The element the control will be created on
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 *   * menu : the menu component
 *   * items : an array of Action models
 *   * contentElement : the element where the subitems will be displayed.
 * @return {mad.component.ButtonDropdown}
 */
var ButtonDropdown = mad.component.ButtonDropdown = mad.component.Button.extend('mad.component.ButtonDropdown', {

    defaults: {
        label: 'Button Dropdown Component',
        viewClass: mad.view.component.ButtonDropdown,
        // The menu items.
        items: null,
        // Customize the element which will carry the dropdown content
        contentElement: null,
        // Close menu on item click.
        closeOnItemClick: true
    }

}, /** @prototype */ {

    /**
     * After start. Populate the dropdown menu.
     */
    afterStart: function() {
        var $dropdownElement = null;

        // @todo This container should be inserted following a different way
        // If the dropdown content element hasn't been customized, inject one in DOM.
        if (this.options.contentElement == null) {
            $dropdownElement = $('<ul class="dropdown-content"></div>').insertAfter(this.element);
        }
        else {
            $dropdownElement = $(this.options.contentElement);
        }

        // Create and render dropdown content.
        this.options.menu = new mad.component.Menu($dropdownElement);
        this.options.menu.start();
        this.options.menu.load(this.options.items);
        this.on();
    },

    /**
     * Set the item state.
     * @param id The item id.
     * @param stateName The state to set.
     */
    setItemState: function(id, stateName) {
        this.options.menu.setItemState(id, stateName);
    },

    /**
     * Close menu when clicking on an item.
     * @param el
     * @param ev
     * @param item
     */
    '{menu.element} item_selected': function(el, ev, item) {
        if (this.options.closeOnItemClick === true  && !item.state.is('disabled')) {
            this.view.close();
        }
    },

    /* ************************************************************** */
    /* LISTEN TO THE STATE CHANGES */
    /* ************************************************************** */

    /**
     * Listen to the change relative to the state Disabled
     * @parent mad.component.ButtonDropdown.state_changes
     * @param {boolean} go Enter or leave the state
     * @return {void}
     */
    stateDisabled: function (go) {
        this._super(go);
        if (go) {
            this.view.close();
        }
    }
});

export default ButtonDropdown;