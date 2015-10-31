import "steal-mocha";
import mad from "mad"
import "mad/component/dialog"
import "mad/component/confirm"
var expect = chai.expect;
var assert = chai.assert;

describe("mad.component.Confirm", function () {

    //// Clean the DOM after each test.
    afterEach(function () {
        $('.dialog-wrapper').remove();
    });

    it("constructed instance should inherit mad.component.FreeComposite & the inherited parent classes", function () {
        var confirm = new mad.component.Confirm(null, {label: 'Confirm Test'}).start();

        // Basic control of classes inheritance.
        expect(confirm).to.be.instanceOf(can.Control);
        expect(confirm).to.be.instanceOf(mad.Control);
        expect(confirm).to.be.instanceOf(mad.Component);
        expect(confirm).to.be.instanceOf(mad.component.Composite);
        expect(confirm).to.be.instanceOf(mad.component.FreeComposite);
        expect(confirm).to.be.instanceOf(mad.component.Dialog);

        confirm.start();
    });

    it("Confirm should be visible in the dom after start", function () {
        expect($('.dialog-wrapper').length).to.equal(0);
        var configm = new mad.component.Confirm(null, {label: 'Confirm Test'}).start();
        expect($('.dialog').length).to.not.equal(0);

        expect($('.dialog').html()).to.contain('Confirm Test');
        expect($('.dialog').html()).to.contain('close');
    });

    it("Confirm should be hidden after clicking on close", function () {
        var confirm = new mad.component.Confirm(null, {label: 'Confirm Test'}).start();
        expect($('.dialog').length).to.not.equal(0);
        $('a.dialog-close').click();
        expect($('.dialog').length).to.equal(0);
    });

    it("Confirm should be hidden after clicking on cancel button", function () {
        var confirm = new mad.component.Confirm(null, {label: 'Confirm Test'}).start();
        expect($('.dialog').length).to.not.equal(0);
        $('a.js-dialog-cancel').click();
        expect($('.dialog').length).to.equal(0);
    });

    it("Action should be executed after clicking on Ok button", function () {
        $('body').append('<p class="feedback"></p>');
        var confirm = new mad.component.Confirm(
            null,
            {
                label: 'Confirm Test',
                action: function() {
                    $('p.feedback').text('action is executed');
                }
            }
        ).start();
        expect($('.dialog').length).to.not.equal(0);
        $('#confirm-button').click();
        expect($('.dialog').length).to.equal(0);
        expect($('p.feedback').html()).to.equal('action is executed');
        $('p.feedback').remove();
    });

    it("Confirm dialog should not be closed after action if closeAfterAction is set to false", function () {
        $('body').append('<p class="feedback"></p>');
        var confirm = new mad.component.Confirm(
            null,
            {
                label: 'Confirm Test',
                action: function() {
                    $('p.feedback').text('action1 is executed');
                },
                closeAfterAction: false
            }
        ).start();
        expect($('.dialog').length).to.not.equal(0);
        $('#confirm-button').click();
        expect($('.dialog').length).to.not.equal(0);
        expect($('p.feedback').html()).to.equal('action1 is executed');
        $('p.feedback').remove();
    });

    it("Content should be displayed as per what is given in the variables", function () {
        var confirm = new mad.component.Confirm(
            null,
            {
                label: 'Confirm Test',
                content: 'Content of the confirm box'
            }
        ).start();
        expect($('.dialog').length).to.not.equal(0);
        expect($('.dialog-content').text()).to.contain('Content of the confirm box');

        // Test function set content.
        confirm.setContent('new content of the confirm box');
        expect($('.dialog-content').text()).to.contain('new content of the confirm box');
    });
});