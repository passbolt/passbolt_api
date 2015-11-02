import "test/bootstrap";
import "mad/form/element"

describe("mad.form.Element", function () {

    // The HTMLElement which will carry the textbox component.
    var $element = null;

    // Insert a <ul> HTMLElement into the DOM for the test.
    beforeEach(function () {
        $element = $('<input type="text" id="element"/>').appendTo($('#test-html'));
    });

    // Clean the DOM after each test.
    afterEach(function () {
        $('#test-html').empty();
    });

    it("constructed instance should inherit mad.form.Element & the inherited parent classes", function () {
        var element = new mad.form.Element($element, {});

        // Basic control of classes inheritance.
        expect(element).to.be.instanceOf(can.Control);
        expect(element).to.be.instanceOf(mad.Component);
        expect(element).to.be.instanceOf(mad.form.Element);

        element.destroy();
    });

    it("setValue() should change the value of the form element", function () {
        var element = new mad.form.Element($element, {});
        element.start();

        expect(element.getValue()).to.be.null;
        element.setValue('abc');
        expect(element.getValue()).to.be.equal('abc');

        element.destroy();
    });

    it("Switching the form element state to disabled should add a disabled attribute", function () {
        var element = new mad.form.Element($element, {});
        element.start();

        expect($element.attr('disabled')).to.be.undefined;
        element.setState('disabled');
        expect($element.attr('disabled')).to.be.equal('disabled');

        element.destroy();
    });

});