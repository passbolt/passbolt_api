import "test/bootstrap";
import "mad/component/tree"

describe("mad.component.Tree", function () {

    // The HTMLElement which will carry the tree component.
    var $tree = null;

    // Insert a <ul> HTMLElement into the DOM for the test.
    beforeEach(function () {
        $tree = $('<ul id="tree"></ul>').appendTo($('#test-html'));
    });

    // Clean the DOM after each test.
    afterEach(function () {
        $('#test-html').empty();
    });

    it("constructed instance should inherit mad.component.Tree & the inherited parent classes", function () {
        var tree = new mad.component.Tree($tree, {
            itemClass: mad.Model
        });

        // Basic control of classes inheritance.
        expect(tree).to.be.instanceOf(can.Control);
        expect(tree).to.be.instanceOf(mad.Control);
        expect(tree).to.be.instanceOf(mad.Component);
        expect(tree).to.be.instanceOf(mad.component.Tree);

        tree.start();
        tree.destroy();
    });

    it("insertItem() should insert an item into the tree", function () {
        var tree = new mad.component.Tree($tree, {
            itemClass: mad.Model
        });
        tree.start();

        // Insert a first item.
        var itemInside = new mad.Model({
            id: 'item_inside',
            label: 'item inside label'
        });
        tree.insertItem(itemInside);
        expect($('#test-html').text()).to.contain(itemInside.attr('label'));

        // Insert an item before the first one.
        var itemBefore = new mad.Model({
            id: 'item_before',
            label: 'item before label'
        });
        tree.insertItem(itemBefore, itemInside, 'before');
        expect($tree.text()).to.contain(itemBefore.attr('label'));
        expect(tree.view.getItemElement(itemInside).prev().attr('id')).to.be.equal('item_before');

        // Insert an item after the before one.
        var itemAfter = new mad.Model({
            id: 'item_after',
            label: 'item after label'
        });
        tree.insertItem(itemAfter, itemBefore, 'after');
        expect($tree.text()).to.contain(itemInside.attr('label'));
        expect(tree.view.getItemElement(itemInside).prev().attr('id')).to.be.equal('item_after');
        expect(tree.view.getItemElement(itemBefore).next().attr('id')).to.be.equal('item_after');

        // Insert a child.
        var itemChildInside = new mad.Model({
            id: 'item_child_inside',
            label: 'item child inside label'
        });
        tree.insertItem(itemChildInside, itemBefore, 'last');
        expect($tree.text()).to.contain(itemChildInside.attr('label'));
        expect(tree.view.getItemElement(itemBefore).find('ul:first').children().attr('id')).to.be.equal('item_child_inside');

        // Insert a child in first position.
        var itemChildFirst = new mad.Model({
            id: 'item_child_first',
            label: 'item child first label'
        });
        tree.insertItem(itemChildFirst, itemBefore, 'first');
        expect($tree.text()).to.contain(itemChildFirst.attr('label'));
        expect(tree.view.getItemElement(itemChildInside).prev().attr('id')).to.be.equal('item_child_first');

        // Test inserting an element in first position without providing a refItem.
        var itemFirst = new mad.Model({
            id: 'item_first',
            label: 'item first label'
        });
        tree.insertItem(itemFirst, null, 'first');
        expect($tree.text()).to.contain(itemFirst.attr('label'));
        expect(tree.view.getItemElement(itemBefore).prev().attr('id')).to.be.equal('item_first');

        tree.element.empty();
        tree.destroy();
    });

    it('load() should insert several items in the tree', function () {
        var tree = new mad.component.Tree($tree, {
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

        expect($tree.text()).to.contain('Item 1');
        expect($tree.text()).to.contain('Item 2');
        expect($tree.text()).to.contain('Item 21');
        expect($tree.text()).to.contain('Item 22');
        expect($tree.text()).to.contain('Item 3');
    });

    it("removeItem() should remove an item from the tree - root level", function () {
        var tree = new mad.component.Tree($tree, {
            itemClass: mad.Model
        });
        tree.start();

        // Insert items at root level.
        var items = [];
        for (var i = 0; i < 5; i++) {
            items[i] = new mad.Model({
                id: 'item_inside_' + i,
                label: 'item inside label ' + i
            });
            tree.insertItem(items[i]);
            expect($('#test-html').text()).to.contain(items[i].attr('label'));
        }

        // Remove an item.
        tree.removeItem(items[2]);
        // Check that the item we removed is not present anymore, but the other are still there.
        expect($('#test-html').text()).not.to.contain(items[2].attr('label'));
        expect($('#test-html').text()).to.contain(items[0].attr('label'));
        expect($('#test-html').text()).to.contain(items[1].attr('label'));
        expect($('#test-html').text()).to.contain(items[3].attr('label'));
        expect($('#test-html').text()).to.contain(items[4].attr('label'));

        tree.element.empty();
        tree.destroy();
    });

    it("removeItem() should remove an item from the tree - nested levels", function () {
        var tree = new mad.component.Tree($tree, {
            itemClass: mad.Model
        });
        tree.start();

        // Insert items an child items.
        var items = [];
        var subItems = [];
        for (var i = 0; i < 5; i++) {
            items[i] = new mad.Model({
                id: 'item_inside_' + i,
                label: 'item inside label ' + i
            });
            tree.insertItem(items[i]);
            expect($('#test-html').text()).to.contain(items[i].attr('label'));

            // Insert nested element.
            subItems[i] = [];
            for (var j = 0; j < 5; j++) {
                subItems[i][j] = new mad.Model({
                    id: 'sub_item_inside_' + i + '_' + j,
                    label: 'sub item label ' + i + ' ' + j
                });
                tree.insertItem(subItems[i][j], items[i]);
                expect($('#test-html').text()).to.contain(subItems[i][j].attr('label'));
            }
        }

        // Remove an item.
        tree.removeItem(subItems[2][2]);
        // Check that the item we removed is not present anymore, but the other are still there.
        expect($('#test-html').text()).not.to.contain(subItems[2][2].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[2][0].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[2][1].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[2][3].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[2][4].attr('label'));
        expect($('#test-html').text()).to.contain(items[0].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[0][0].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[0][1].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[0][2].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[0][3].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[0][4].attr('label'));
        expect($('#test-html').text()).to.contain(items[1].attr('label'));
        expect($('#test-html').text()).to.contain(items[3].attr('label'));
        expect($('#test-html').text()).to.contain(items[4].attr('label'));

        // Remove an item.
        tree.removeItem(items[4]);
        // Check that the item we removed is not present anymore, but the other are still there.
        expect($('#test-html').text()).not.to.contain(subItems[2][2].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[2][0].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[2][1].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[2][3].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[2][4].attr('label'));
        expect($('#test-html').text()).to.contain(items[0].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[0][0].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[0][1].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[0][2].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[0][3].attr('label'));
        expect($('#test-html').text()).to.contain(subItems[0][4].attr('label'));
        expect($('#test-html').text()).to.contain(items[1].attr('label'));
        expect($('#test-html').text()).to.contain(items[3].attr('label'));
        expect($('#test-html').text()).not.to.contain(items[4].attr('label'));
        expect($('#test-html').text()).not.to.contain(subItems[4][0].attr('label'));
        expect($('#test-html').text()).not.to.contain(subItems[4][1].attr('label'));
        expect($('#test-html').text()).not.to.contain(subItems[4][2].attr('label'));
        expect($('#test-html').text()).not.to.contain(subItems[4][3].attr('label'));
        expect($('#test-html').text()).not.to.contain(subItems[4][4].attr('label'));

        tree.element.empty();
        tree.destroy();
    });

    it("refreshItem() should refresh an item in the tree", function () {
        var tree = new mad.component.Tree($tree, {
            itemClass: mad.Model
        });
        tree.start();

        // Insert a first item.
        var item = new mad.Model({
            id: 'item_inside',
            label: 'item inside label'
        });
        tree.insertItem(item);
        expect($('#test-html').text()).to.contain(item.attr('label'));

        item.attr('label', 'item inside updated label');
        tree.refreshItem(item);
        expect($('#test-html').text()).to.contain(item.attr('label'));

        tree.element.empty();
        tree.destroy();
    });

    it("selectItem() should select an item in the tree", function () {
        var tree = new mad.component.Tree($tree, {
            itemClass: mad.Model
        });
        tree.start();

        // Insert an item.
        var item = new mad.Model({
            id: 'item',
            label: 'item label'
        });
        tree.insertItem(item);

        // By default an item shouldn't be selected.
        var $item = $('#test-html #' + item.attr('id'));
        expect($('.row:first', $item).hasClass('selected')).to.be.false;

        // Select an item manually and test that it has been selected.
        tree.selectItem(item);
        expect($('.row:first', $item).hasClass('selected')).to.be.true;

        // Unselect all items.
        tree.unselectItem(item);
        expect($('.row:first', $item).hasClass('selected')).to.be.false;

        // Select an item by clicking on it.
        $('a', $item).trigger('click');
        expect($('.row:first', $item).hasClass('selected')).to.be.true;

        tree.element.empty();
        tree.destroy();
    });

    it("selectItem() should select an item in the tree after refresh", function () {
        var tree = new mad.component.Tree($tree, {
            itemClass: mad.Model
        });
        tree.start();

        // Insert an item.
        var item = new mad.Model({
            id: 'item',
            label: 'item label'
        });
        tree.insertItem(item);
        item.attr('label', 'item label updated');
        tree.refreshItem(item);

        // By default an item shouldn't be selected.
        var $item = $('#test-html #' + item.attr('id'));
        expect($('.row:first', $item).hasClass('selected')).to.be.false;

        // Select an item manually and test that it has been selected.
        tree.selectItem(item);
        expect($('.row:first', $item).hasClass('selected')).to.be.true;

        // Unselect all items.
        tree.unselectItem(item);
        expect($('.row:first', $item).hasClass('selected')).to.be.false;

        // Select an item by clicking on it.
        $('a', $item).trigger('click');
        expect($('.row:first', $item).hasClass('selected')).to.be.true;

        tree.element.empty();
        tree.destroy();
    });

});
