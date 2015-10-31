import $ from "jquery";
import mad from "mad/mad";
import Grid from "mad/component/grid";

// Set the grid map that will be used to transform the data for the view.
var map = new mad.Map({
    id: 'id',
    label: 'label'
});
// Set the grid columns model.
var columnModel = [{
    name: 'id',
    index: 'id',
    header: {
        label: 'id',
        css: []
    }
}, {
    name: 'label',
    index: 'label',
    header: {
        label: 'label',
        css: []
    }
}];
var grid = new mad.component.Grid($('#grid'), {
    itemClass: mad.Model,
    map: map,
    columnModel: columnModel
});
grid.start();

var items = new mad.Model.List([{
    id: 'item_1',
    label: 'Item 1'
}, {
    id: 'item_2',
    label: 'Item 2'
}, {
    id: 'item_3',
    label: 'Item 3'
}]);

grid.load(items);
