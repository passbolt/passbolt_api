import "test/bootstrap";
import "mad/component/dynamic_tree"

describe("mad.component.DynamicTree", function () {

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
        var tree = new mad.component.DynamicTree($tree, {
            itemClass: mad.Model
        });

        // Basic control of classes inheritance.
        expect(tree).to.be.instanceOf(can.Control);
        expect(tree).to.be.instanceOf(mad.Control);
        expect(tree).to.be.instanceOf(mad.Component);
        expect(tree).to.be.instanceOf(mad.component.Tree);
        expect(tree).to.be.instanceOf(mad.component.DynamicTree);

        tree.start();
        tree.destroy();
    });

    it('open() and close() should open and close the corresponding sections of the tree', function () {
        var tree = new mad.component.DynamicTree($tree, {
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

        expect($('#item_2').hasClass('close')).to.be.true;
        tree.open(items[1]);
        expect($('#item_2').hasClass('close')).to.be.false;
        expect($('#item_2').hasClass('open')).to.be.true;
        tree.close(items[1]);
        expect($('#item_2').hasClass('close')).to.be.true;
    });

    it('Clicking on the open/close trigger should open and close the corresponding section of the tree', function () {
        var tree = new mad.component.DynamicTree($tree, {
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

        expect($('#item_2').hasClass('close')).to.be.true;
        $('#item_2 .left-cell.node-ctrl > a').click();
        expect($('#item_2').hasClass('close')).to.be.false;
        expect($('#item_2').hasClass('open')).to.be.true;
        $('#item_2 .left-cell.node-ctrl > a').click();
        expect($('#item_2').hasClass('close')).to.be.true;
    });
});