import $ from "jquery";
import mad from "mad/mad";
import ButtonDropdown from "mad/component/button_dropdown";
import Action from "mad/model/action";

// Create a list of actions.
var menuItems = [
    new mad.model.Action({
        'id': '1',
        'label': 'action 1',
        'cssClasses': ['todo'],
        'action': function () {
            alert('item 1 clicked');
        }
    }),
    new mad.model.Action({
        'id': '2',
        'label': 'action 2',
        'cssClasses': ['todo'],
        'action': function () {
            alert('item 2 clicked');
        }
    })
];

var buttonDropdown = new mad.component.ButtonDropdown($('#button-dropdown'), {
    'items': menuItems
}).start();
