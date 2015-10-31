import $ from "jquery";
import mad from "mad/mad";
import Tree from "mad/component/tree";

var tree = new mad.component.Tree($('#tree'), {
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
        label: 'Item 21'
    }, {
        id: 'item_22',
        label: 'Item 22'
    }]
}, {
    id: 'item_3',
    label: 'Item 3'
}]);

tree.load(items);
