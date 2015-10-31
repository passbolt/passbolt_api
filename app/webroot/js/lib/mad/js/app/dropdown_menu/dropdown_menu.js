import $ from "jquery";
import mad from "mad/mad";
import Menu from "mad/component/dropdown_menu";
import Action from "mad/model/action";

// Add a link to filter on all items as first item.
var menuItems = [];
var menuItem = new mad.model.Action({
    id: 'el1',
    label: 'item 1',
    action: function () {
        alert('item 1 clicked');
    }
});
menuItems.push(menuItem);
var menuItem = new mad.model.Action({
    id: 'el2',
    label: 'item 2',
    action: function () {
        alert('item 2 clicked');
    }
});
menuItems.push(menuItem);


var menuSelector = 'ul#dropdown-menu';
var menu = new mad.component.DropdownMenu(menuSelector);
menu.start();
menu.load(menuItems);
menu.close(menuItem);
