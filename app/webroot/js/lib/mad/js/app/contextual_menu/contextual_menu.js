import $ from "jquery";
import mad from "mad/mad";
import ContextualMenu from "mad/component/contextual_menu";
import Action from "mad/model/action";

/**
 * Show Contextual menu.
 * @param x
 * @param y
 * @returns {mad.controller.component.ContextualMenuController}
 */
function showContextualMenu($item) {
    var item_offset = $item.offset();

    // Instantiate the contextual menu menu.
    var contextualMenu = new mad.component.ContextualMenu(null, {
        'state': 'hidden',
        'source': $item[0],
        'coordinates': {
            x: item_offset.left,
            y: item_offset.top
        }
    });
    contextualMenu.start();

    // Add a link to filter on all items as first item.
    var menuItems = [];
    var menuItem = new mad.model.Action({
        id: 'el1',
        label: 'item 1',
        action: function () {
            alert('item 1 clicked');
        }
    });
    contextualMenu.insertItem(menuItem);
    var menuItem = new mad.model.Action({
        id: 'el2',
        label: 'item 2',
        action: function () {
            alert('item 2 clicked');
        }
    });
    contextualMenu.insertItem(menuItem);
    // Display the menu.
    contextualMenu.setState('ready');
    return contextualMenu;
}

var menuSelector = 'a#contextual-menu-trigger';
var $menuSelector = $(menuSelector);

$menuSelector.click(function() {
    showContextualMenu($menuSelector);
    return false;
})