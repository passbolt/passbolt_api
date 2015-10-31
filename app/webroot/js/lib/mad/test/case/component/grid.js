import "test/bootstrap";
import "mad/component/grid";

describe("mad.component.Grid", function () {

    // The HTMLElement which will carry the grid component.
    var $grid = null;

    // Insert a <ul> HTMLElement into the DOM for the test.
    beforeEach(function () {
        $grid = $('<div id="grid"></div>').appendTo($('#test-html'));
    });

    // Clean the DOM after each test.
    afterEach(function () {
        $('#test-html').empty();
    });

    it("constructed instance should inherit mad.Grid & the inherited parent classes", function () {
        var tree = new mad.component.Grid($grid, {
            itemClass: mad.Model
        });

        // Basic control of classes inheritance.
        expect(tree).to.be.instanceOf(can.Control);
        expect(tree).to.be.instanceOf(mad.Control);
        expect(tree).to.be.instanceOf(mad.Component);
        expect(tree).to.be.instanceOf(mad.component.Grid);

        tree.start();
        tree.destroy();
    });

    it("insertItem() requires the map option to be defined", function () {
        var grid = new mad.component.Grid($grid, {
            itemClass: mad.Model
        });
        grid.start();

        // Insert a first item.
        var itemInside = new mad.Model({
            id: 'item_inside',
            label: 'item inside label'
        });

        // Asserts.
        expect(function () {
            grid.insertItem(itemInside);
        }).to.throw(Error); // should work but doesn't : mad.Exception.get(mad.error.MISSING_OPTION, 'map')
    });

    it("insertItem() should insert an item into the grid", function () {
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
        var grid = new mad.component.Grid($grid, {
            itemClass: mad.Model,
            map: map,
            columnModel: columnModel
        });
        grid.start();

        // Insert a first item.
        var itemInside = new mad.Model({
            id: 'item_inside',
            label: 'item inside label'
        });
        grid.insertItem(itemInside);
        expect($('#test-html').text()).to.contain(itemInside.attr('label'));

        // Insert an item before the first one.
        var itemBefore = new mad.Model({
            id: 'item_before',
            label: 'item before label'
        });
        grid.insertItem(itemBefore, itemInside, 'before');
        expect($grid.text()).to.contain(itemBefore.attr('label'));
        expect(grid.view.getItemElement(itemInside).prev().attr('id')).to.be.equal('item_before');

        // Insert an item after the before one.
        var itemAfter = new mad.Model({
            id: 'item_after',
            label: 'item after label'
        });
        grid.insertItem(itemAfter, itemBefore, 'after');
        expect($grid.text()).to.contain(itemInside.attr('label'));
        expect(grid.view.getItemElement(itemInside).prev().attr('id')).to.be.equal('item_after');
        expect(grid.view.getItemElement(itemBefore).next().attr('id')).to.be.equal('item_after');

        // Insert an item in first.
        var itemFirst = new mad.Model({
            id: 'item_first',
            label: 'item first label'
        });
        grid.insertItem(itemFirst, null, 'first');
        expect($grid.text()).to.contain(itemFirst.attr('label'));
        expect(grid.view.getItemElement(itemBefore).prev().attr('id')).to.be.equal('item_first');

        // Insert an item in last.
        var itemLast = new mad.Model({
            id: 'item_last',
            label: 'item last label'
        });
        grid.insertItem(itemLast, null, 'last');
        expect($grid.text()).to.contain(itemLast.attr('label'));
        expect(grid.view.getItemElement(itemInside).next().attr('id')).to.be.equal('item_last');

        grid.element.empty();
        grid.destroy();
    });

    it("insertItem() should insert an item and apply a value adapter on its fields", function () {
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
            },
            valueAdapter: function(value, mappedItem, item, columnModel) {
                return 'value adapted : ' + value;
            }
        }];
        var grid = new mad.component.Grid($grid, {
            itemClass: mad.Model,
            map: map,
            columnModel: columnModel
        });
        grid.start();

        // Insert the item.
        var itemInside = new mad.Model({
            id: 'item',
            label: 'item label'
        });
        grid.insertItem(itemInside);
        expect($('#test-html').text()).to.contain('value adapted : ' + itemInside.attr('label'));
    });

    it("insertItem() should insert an item and apply a cell adapter on the target cell", function () {
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
            },
            'cellAdapter': function (cellElement, cellValue, mappedItem, item, columnModel) {
                var html = '<p>Cell adapted applied : ' + cellValue + '</p>';
                mad.helper.Html.create(cellElement, 'inside_replace', html);
            }
        }];
        var grid = new mad.component.Grid($grid, {
            itemClass: mad.Model,
            map: map,
            columnModel: columnModel
        });
        grid.start();

        // Insert the item.
        var itemInside = new mad.Model({
            id: 'item',
            label: 'item label'
        });
        grid.insertItem(itemInside);
        expect($('#test-html').text()).to.contain('Cell adapted applied : ' + itemInside.attr('label'));
    });

    it('load() should insert several items in the grid', function () {
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
        var grid = new mad.component.Grid($grid, {
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

        expect($grid.text()).to.contain('Item 1');
        expect($grid.text()).to.contain('Item 2');
        expect($grid.text()).to.contain('Item 3');
    });

    it("refreshItem() should refresh an item row with an updated items", function(){
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
        var grid = new mad.component.Grid($grid, {
            itemClass: mad.Model,
            map: map,
            columnModel: columnModel
        });
        grid.start();

        // Insert a first item.
        var item = new mad.Model({
            id: 'item',
            label: 'item label'
        });
        grid.insertItem(item);
        expect($('#test-html').text()).to.contain(item.attr('label'));

        // Update the item and refresh the grid.
        item.attr('label', 'updated item label');
        grid.refreshItem(item);
        expect($('#test-html').text()).to.contain('updated item label');
    });

    it("removeItem() should remove an item from the grid", function(){
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
        var grid = new mad.component.Grid($grid, {
            itemClass: mad.Model,
            map: map,
            columnModel: columnModel
        });
        grid.start();

        // Insert items at root level.
        var items = [];
        for (var i = 0; i<5; i++) {
            items[i] = new mad.Model({
                id: 'item_' + i,
                label: 'item label ' + i
            });
            grid.insertItem(items[i]);
            expect($('#test-html').text()).to.contain(items[i].attr('label'));
        }

        // Remove an item.
        grid.removeItem(items[2]);
        // Check that the item we removed is not present anymore, but the other are still there.
        expect($('#test-html').text()).not.to.contain(items[2].attr('label'));
        expect($('#test-html').text()).to.contain(items[0].attr('label'));
        expect($('#test-html').text()).to.contain(items[1].attr('label'));
        expect($('#test-html').text()).to.contain(items[3].attr('label'));
        expect($('#test-html').text()).to.contain(items[4].attr('label'));

        grid.element.empty();
        grid.destroy();
    });

    it("selectItem() should select an item in the grid", function(){
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
        var grid = new mad.component.Grid($grid, {
            itemClass: mad.Model,
            map: map,
            columnModel: columnModel,
            callbacks: {
                itemSelected: function(el, ev, item, srcEvent) {
                    grid.selectItem(item);
                }
            }
        });
        grid.start();

        // Insert a first item.
        var item = new mad.Model({
            id: 'item_inside',
            label: 'item inside label'
        });
        grid.insertItem(item);
        expect($('#test-html').text()).to.contain(item.attr('label'));

        // By default an item shouldn't be selected.
        var $item = $('#test-html #' + item.attr('id'));
        expect($item.hasClass('selected')).to.be.false;

        // Select an item by clicking on it.
        $item.trigger('click');
        expect($item.hasClass('selected')).to.be.true;

        // Unselect all items.
        grid.unselectItem(item);
        expect($item.hasClass('selected')).to.be.false;

        grid.element.empty();
        grid.destroy();
    });

    it("selectItem() should select an item in the grid after refresh", function(){
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
        var grid = new mad.component.Grid($grid, {
            itemClass: mad.Model,
            map: map,
            columnModel: columnModel,
            callbacks: {
                itemSelected: function(el, ev, item, srcEvent) {
                    grid.selectItem(item);
                }
            }
        });
        grid.start();

        // Insert a first item.
        var item = new mad.Model({
            id: 'item_inside',
            label: 'item inside label'
        });
        grid.insertItem(item);
        item.attr('item inside label updated');
        grid.refreshItem(item);
        expect($('#test-html').text()).to.contain(item.attr('label'));

        // By default an item shouldn't be selected.
        var $item = $('#test-html #' + item.attr('id'));
        expect($item.hasClass('selected')).to.be.false;

        // Select an item by clicking on it.
        $item.trigger('click');
        expect($item.hasClass('selected')).to.be.true;

        // Unselect all items.
        grid.unselectItem(item);
        expect($item.hasClass('selected')).to.be.false;

        grid.element.empty();
        grid.destroy();
    });

});
