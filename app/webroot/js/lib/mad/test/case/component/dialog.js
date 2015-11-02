import "steal-mocha";
import mad from "mad"
import "mad/component/dialog"
var expect = chai.expect;
var assert = chai.assert;

describe("mad.component.Dialog", function () {

    //// Clean the DOM after each test.
    afterEach(function () {
        $('.dialog-wrapper').remove();
    });

    it("constructed instance should inherit mad.component.FreeComposite & the inherited parent classes", function () {
        var dialog = new mad.component.Dialog(null, {label: 'Dialog Test'}).start();

        // Basic control of classes inheritance.
        expect(dialog).to.be.instanceOf(can.Control);
        expect(dialog).to.be.instanceOf(mad.Control);
        expect(dialog).to.be.instanceOf(mad.Component);
        expect(dialog).to.be.instanceOf(mad.component.Composite);
        expect(dialog).to.be.instanceOf(mad.component.FreeComposite);

        dialog.start();
    });

    it("Dialog should be visible in the dom after start", function () {
        expect($('.dialog-wrapper').length).to.equal(0);
        var dialog = new mad.component.Dialog(null, {label: 'Dialog Test'}).start();
        expect($('.dialog').length).to.not.equal(0);

        expect($('.dialog').html()).to.contain('Dialog Test');
        expect($('.dialog').html()).to.contain('close');
    });

    it("Dialog should be hidden after clicking on close", function () {
        var dialog = new mad.component.Dialog(null, {label: 'Dialog Test'}).start();
        expect($('.dialog').length).to.not.equal(0);
        $('a.dialog-close').click();
        expect($('.dialog').length).to.equal(0);
    });
});