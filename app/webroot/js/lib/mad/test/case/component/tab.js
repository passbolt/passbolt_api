import "steal-mocha";
import mad from "mad";
import "mad/component/tab";
var expect = chai.expect;
var assert = chai.assert;

describe("mad.component.Tab", function () {

    // The HTMLElement which will carry the tab component.
    var $tab = null;

    // Insert a <ul> HTMLElement into the DOM for the test.
    beforeEach(function () {
        $tab = $('<div id="tab"></div>').appendTo($('#test-html'));
    });

    // Clean the DOM after each test.
    afterEach(function () {
        $('#test-html').empty();
    });


    it("constructed instance should inherit mad.component.Tab & the inherited parent classes", function () {
        var tabs = new mad.component.Tab('#tab');

        // Basic control of classes inheritance.
        expect(tabs).to.be.instanceOf(can.Control);
        expect(tabs).to.be.instanceOf(mad.Control);
        expect(tabs).to.be.instanceOf(mad.Component);
        expect(tabs).to.be.instanceOf(mad.component.Composite);
        expect(tabs).to.be.instanceOf(mad.component.Tab);

        tabs.start();
        tabs.destroy();
    });

    it("Tabs should appear and disappear on click", function () {
        var tabs = new mad.component.Tab('#tab', {
            autoMenu: true
        });
        tabs.start();

        // Tab 1.
        var tab1 = tabs.addComponent(mad.Component, {
            id: 'free-composite-1',
            label: 'tab1',
            templateBased: false
        });

        // Tab2.
        var tab2 = tabs.addComponent(mad.Component, {
            id: 'free-composite-2',
            label: 'tab2',
            templateBased: false
        });
        tabs.enableTab('free-composite-2');
        tabs.enableTab('free-composite-1');

        // Add text inside the tabs.
        $('<p class="txt1">this is the content of tab 1</p>').appendTo('#free-composite-1');
        $('<p class="txt2">this is the content of tab 2</p>').appendTo('#free-composite-2');

        expect($('.tabs-content p:visible').text()).to.equal('this is the content of tab 1');
        $('#js_tab_nav_free-composite-2 a').click();
        expect($('.tabs-content p:visible').text()).to.equal('this is the content of tab 2');
    });

});