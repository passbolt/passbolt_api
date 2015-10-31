import "test/bootstrap";
import "mad/component/button";

describe("mad.component.Button", function () {

    // The HTMLElement which will carry the button component.
    var $button = null;
    var $debugOutput = null;

    // Insert a <ul> HTMLElement into the DOM for the test.
    beforeEach(function () {
        $button = $('<div id="button"></div>').appendTo($('#test-html'));
        $debugOutput = $('<div id="test-output"></div>').appendTo($('#test-html'));
    });

    // Clean the DOM after each test.
    afterEach(function () {
        $('#test-html').empty();
    });

    it("constructed instance should inherit mad.Grid & the inherited parent classes", function () {
        var button = new mad.component.Button($button);

        // Basic control of classes inheritance.
        expect(button).to.be.instanceOf(can.Control);
        expect(button).to.be.instanceOf(mad.Control);
        expect(button).to.be.instanceOf(mad.Component);
        expect(button).to.be.instanceOf(mad.component.Button);

        button.start();
        button.destroy();
    });

    it("a click on button should trigger the associated function", function () {
        var valueTest = 'k3d';
        var button = new mad.component.Button($button, {
            value: valueTest,
            events: {
                'click': function (el, ev, value) {
                    $debugOutput.html(value);
                }
            }
        });
        button.start();

        $button.click();
        expect($debugOutput.text()).to.contain(valueTest);

        button.destroy();
    });

    it("state disabled should be intercepted", function () {
        var button = new mad.component.Button($button);
        button.start();

        button.setState('disabled');
        expect($button.attr('class')).to.contain('disabled');

        button.destroy();
    });

    it("click should not be executed if the state is disabled", function () {
        var valueTest = 'k3d';
        var button = new mad.component.Button($button, {
            value: valueTest,
            events: {
                'click': function (el, ev, value) {
                    $debugOutput.html(value);
                }
            }
        });
        button.start();

        button.setState('disabled');
        $button.click();
        expect($debugOutput.text()).to.not.contain(valueTest);

        button.destroy();
    });
});