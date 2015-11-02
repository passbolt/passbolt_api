import "test/bootstrap";
import "mad/component/contextual_menu";

describe("mad.component.ContextualMenu", function () {

    // The HTMLElement which will carry the button component.
    var $menu = null;
    var $debugOutput = null;

    // Insert a <ul> HTMLElement into the DOM for the test.
    beforeEach(function () {
        $menu = $('<a id="menu">Test Contextual Menu</a>').appendTo($('#test-html'));
        $debugOutput = $('<div id="test-output"></div>').appendTo($('#test-html'));
    });

    // Clean the DOM after each test.
    afterEach(function () {
        $('#test-html').empty();
    });

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
            label: 'Item 1',
            action: function () {
                $debugOutput.html('item 1 clicked');
            }
        });
        contextualMenu.insertItem(menuItem);
        var menuItem = new mad.model.Action({
            id: 'el2',
            label: 'Item 2',
            action: function () {
                $debugOutput.html('item 2 clicked');
            }
        });
        contextualMenu.insertItem(menuItem);
        // Display the menu.
        contextualMenu.setState('ready');
        return contextualMenu;
    }

    it("constructed instance should inherit mad.component.DropdownMenu & the inherited parent classes", function () {
        var menu = new mad.component.ContextualMenu(null, {
            'state': 'hidden',
            'source': $menu,
            'coordinates': {
                x: 0,
                y: 0
            }
        });

        // Basic control of classes inheritance.
        expect(menu).to.be.instanceOf(can.Control);
        expect(menu).to.be.instanceOf(mad.Control);
        expect(menu).to.be.instanceOf(mad.Component);
        expect(menu).to.be.instanceOf(mad.component.Menu);
        expect(menu).to.be.instanceOf(mad.component.DropdownMenu);

        menu.start();
        menu.destroy();
    });

    it("Contextual menu should be displayed on a click", function () {
        var menu = null;
        $menu.click(function() {
            menu = showContextualMenu($menu);
        });

        expect($('#js_contextual_menu').length).to.equal(0);
        $menu.click();
        expect($('#js_contextual_menu').length).to.not.equal(0);

        expect($('#js_contextual_menu').html()).to.contain('Item 1');
        expect($('#js_contextual_menu').html()).to.contain('Item 2');

        menu.destroy();
    });

    it("Contextual menu should trigger actions while clicking on subitems", function () {
        var menu = null;
        $menu.click(function() {
            menu = showContextualMenu($menu);
        });

        // Click On main menu to display contextual menu.
        $menu.click();

        //// Click on an Item and observe that it triggers the action.
        $('#el1 a', $('#js_contextual_menu')).click();
        expect($debugOutput.text()).to.contain('item 1 clicked');

        menu.destroy();
    });
});