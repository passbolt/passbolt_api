import $ from "jquery";
import mad from "mad/mad";
import Menu from "mad/component/menu";
import Action from "mad/model/action";

var menuSelector = 'ul#menu';
var menu = new mad.component.Menu(menuSelector);
menu.start();

// Add a link to filter on all items as first item.
var menuItems = [];
var menuItem = new mad.model.Action({
    id: '1',
    label: 'item 1',
    action: function () {
        alert('item 1 clicked');
    }
});
menuItems.push(menuItem);
var menuItem = new mad.model.Action({
    id: '2',
    label: 'item 2',
    action: function () {
        alert('item 2 clicked');
    }
});
menuItems.push(menuItem);
menu.load(menuItems);
