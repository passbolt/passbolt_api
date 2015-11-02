import $ from "jquery";
import mad from "mad/mad";
import Tree from "mad/component/dynamic_tree";

var tree = new mad.component.DynamicTree($('#tree'), {
    itemClass: mad.Model
});
tree.start();

var items = new mad.Model.List([{
    id: 'item_1',
    label: 'Item 1'
}, {
    id: 'item_2',
    label: 'Item 2',
    'children': [{
        id: 'item_21',
        label: 'Item 21',
        'children': [{
            id: 'item_211',
            label: 'Item 211'
        }, {
            id: 'item_212',
            label: 'Item 212'
        }]
    }, {
        id: 'item_22',
        label: 'Item 22'
    }]
}, {
    id: 'item_3',
    label: 'Item 3'
}]);

tree.load(items);
