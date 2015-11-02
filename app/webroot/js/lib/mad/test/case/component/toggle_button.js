import "test/bootstrap";
import "mad/component/toggle_button";

describe("mad.component.ToggleButton", function () {

    // The HTMLElement which will carry the button component.
    var $button = null;

    // Insert a <ul> HTMLElement into the DOM for the test.
    beforeEach(function () {
        $button = $('<div id="button"></div>').appendTo($('#test-html'));
    });

    // Clean the DOM after each test.
    afterEach(function () {
        $('#test-html').empty();
    });

    it("constructed instance should inherit mad.Grid & the inherited parent classes", function () {
        var button = new mad.component.ToggleButton($button);

        // Basic control of classes inheritance.
        expect(button).to.be.instanceOf(can.Control);
        expect(button).to.be.instanceOf(mad.Control);
        expect(button).to.be.instanceOf(mad.Component);
        expect(button).to.be.instanceOf(mad.component.Button);
        expect(button).to.be.instanceOf(mad.component.ToggleButton);

        button.start();
        button.destroy();
    });

    it("a click on button should put it in pressed position", function () {
        var button = new mad.component.ToggleButton($button, {});
        button.start();

        $button.click();
        expect($button.hasClass('selected')).to.be.true;
        $button.click();
        expect($button.hasClass('selected')).to.be.false;

        button.destroy();
    });
});