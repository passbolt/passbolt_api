import "test/bootstrap";
import "can/util/object/object";
import Form from "mad/form/form"
import Tree from "mad/component/tree";
import Textbox from "mad/form/element/textbox";
import Checkbox from "mad/form/element/checkbox";
import "test/helper/model";

describe("mad.Form", function () {
    // The HTMLElement which will carry the form component.
    var $form = null;

    // Insert a <form> HTMLElement into the DOM for the test.
    beforeEach(function () {
        $form = $('<form id="form"></form>').appendTo($('#test-html'));
    });

    // Clean the DOM after each test.
    afterEach(function () {
        $('#test-html').empty();
    });

    it("constructed instance should inherit mad.Form & the inherited parent classes", function () {
        var form = new mad.Form($form, {});

        // Basic control of classes inheritance.
        expect(form).to.be.instanceOf(can.Control);
        expect(form).to.be.instanceOf(mad.Component);
        expect(form).to.be.instanceOf(mad.Form);

        form.destroy();
    });

    it("addElement() should not associate not form element to the form", function () {
        var form = new mad.Form($form, {});
        form.start();

        // Try to add a tree to the form.
        $form.append('<ul id="tree"></ul>');
        var tree = new mad.component.Tree($('#tree'), {
            itemClass: mad.Model
        });
        expect(function () {
            form.addElement(tree.start());
        }).to.throw(Error);

        form.destroy();
    });

    it("addElement() should associate form elements to the form", function () {
        var form = new mad.Form($form, {});
        form.start();

        // Add a textbox to the form.
        var $textbox = $('<input id="textbox"/>').appendTo($form),
            textbox = new mad.form.Textbox($textbox, {});

        expect(function () {
            form.addElement(textbox.start());
        }).not.to.throw(Error);

        // Add a second textbox to the form.
        // This textbox is associated to a model reference.
        var $textboxWthModelRef = $('<input id="textbox-wth-model-ref"/>').appendTo($form),
            textboxWthModelRef = new mad.form.Textbox($textboxWthModelRef, {
                modelReference: 'mad.test.model.TestModel.testModelAttribute'
            });

        expect(function () {
            form.addElement(textboxWthModelRef.start());
        }).not.to.throw(Error);
    });

    it("getElement() should return a form element based on its id", function () {
        var form = new mad.Form($form, {});
        form.start();

        // Add a textbox to the form.
        var $textbox = $('<input id="textbox"/>').appendTo($form);
        var textbox = new mad.form.Textbox($textbox, {
            modelReference: 'mad.test.model.TestModel.testModelAttribute'
        });

        // Check that the element is not already added to the form.
        expect(form.getElement(textbox.getId())).to.be.undefined;

        // Add the element to the form and check that we can retrieve it in the form.
        form.addElement(textbox.start());
        var gotTextbox = form.getElement(textbox.getId());
        expect(gotTextbox).not.to.be.undefined;
        expect(can.Object.same(textbox, gotTextbox)).to.be.true;
    });

    it("removeElement() should remove an element from the form", function () {
        var form = new mad.Form($form, {});
        form.start();

        // Add a textbox to the form.
        var $textbox = $('<input id="textbox"/>').appendTo($form);
        var textbox = new mad.form.Textbox($textbox, {
            modelReference: 'mad.test.model.TestModel.testModelAttribute'
        });

        // Add & remove the element from the form, check that the element is not associated to the form.
        form.addElement(textbox.start());
        form.removeElement(textbox);
        var gotTextbox = form.getElement(textbox.getId());
        expect(gotTextbox).to.be.undefined;
    });

    it("getData() should return the data the form elements gathered", function (done) {
        var form = new mad.Form($form, {});
        form.start();

        // Add a textbox to the form.
        var $textbox = $('<input id="textbox"/>').appendTo($form);
        var textbox = new mad.form.Textbox($textbox, {
            modelReference: 'mad.test.model.TestModel.testModelAttribute'
        });
        form.addElement(textbox.start());

        // By default, if no data have been inserted.
        setTimeout(function () {
            // Get the data.
            var data = form.getData();
            expect(textbox.getValue()).to.be.null;
            expect(data['mad.test.model.TestModel'].testModelAttribute).to.be.null;
        }, 0);

        // Insert a value in the textbox.
        $textbox.val('abc');
        $textbox.focus().trigger('input');

        // After all event handlers have done their treatment.
        setTimeout(function () {
            // Get the data.
            var data = form.getData();
            expect(textbox.getValue()).to.be.equal('abc');
            expect(data['mad.test.model.TestModel'].testModelAttribute).to.be.equal('abc');
            done();
        }, 0);
    });

    it("getData() should return the data the form elements gathered for a complex nested model representation", function (done) {
        var form = new mad.Form($form, {});
        form.start();

        // Add a textbox to the form.
        var $textbox = $('<input id="textbox"/>').appendTo($form);
        var textbox = new mad.form.Textbox($textbox, {
            modelReference: 'mad.test.model.TestModel.testModelAttribute'
        });
        form.addElement(textbox.start());

        // Add a second textbox to the form.
        var $textbox2 = $('<input id="textbox2"/>').appendTo($form);
        var textbox2 = new mad.form.Textbox($textbox2, {
            modelReference: 'mad.test.model.TestModel.TestModel1.testModel1Attribute'
        });
        form.addElement(textbox2.start());

        // By default, if no data have been inserted.
        setTimeout(function () {
            // Get the data.
            var data = form.getData();
            expect(textbox.getValue()).to.be.null;
            expect(textbox2.getValue()).to.be.null;
            expect(data['mad.test.model.TestModel'].testModelAttribute).to.be.null;
            expect(data['mad.test.model.TestModel'].TestModel1.testModel1Attribute).to.be.null;

            // Simuate keypress on the textboxes.
            $textbox.val('abc').trigger('input');
            $textbox2.val('xyz').trigger('input');

            // After all event handlers have done their treatment.
            setTimeout(function () {
                // Get the data.
                var data = form.getData();
                expect(textbox.getValue()).to.be.equal('abc');
                expect(textbox2.getValue()).to.be.equal('xyz');
                expect(data['mad.test.model.TestModel'].testModelAttribute).to.be.equal('abc');
                expect(data['mad.test.model.TestModel'].TestModel1.testModel1Attribute).to.be.equal('xyz');
                done();
            }, 0);
        }, 0);
    });

    it("getData() should return the data the form elements gathered for a complex nested multiple model representation", function (done) {
        var form = new mad.Form($form, {});
        form.start();

        // Add a textbox to the form.
        var $textbox = $('<input id="textbox" type="text"/>').appendTo($form);
        var textbox = new mad.form.Textbox($textbox, {
            modelReference: 'mad.test.model.TestModel.testModelAttribute'
        });
        form.addElement(textbox.start());

        // Add a checkbox to the form.
        var $checkbox = $('<div id="checkbox"></div>').appendTo($form);
        var checkbox = new mad.form.Checkbox($checkbox, {
            availableValues: {
                'option_1': 'Option 1',
                'option_2': 'Option 2',
                'option_3': 'Option 3'
            },
            modelReference: 'mad.test.model.TestModel.TestModel1s.testModel1Attribute'
        });
        form.addElement(checkbox.start());

        // By default, if no data have been inserted.
        setTimeout(function () {
            // Get the data.
            var data = form.getData();

            expect(textbox.getValue()).to.be.null;
            expect(checkbox.getValue()).to.be.null;
            expect(data['mad.test.model.TestModel'].testModelAttribute).to.be.null;
            expect(data['mad.test.model.TestModel'].TestModel1s).to.be.empty;

            // Simulate inputs.
            $textbox.val('abc').trigger('input');
            $('input[value=option_1]', $checkbox).click();
            $('input[value=option_2]', $checkbox).click();

            // After all event handlers have done their treatment.
            setTimeout(function () {
                // Get the data.
                var data = form.getData();
                expect(textbox.getValue()).to.be.equal('abc');
                expect(checkbox.getValue()).to.be.eql(['option_1', 'option_2']);
                expect(data['mad.test.model.TestModel'].testModelAttribute).to.be.equal('abc');
                expect(data['mad.test.model.TestModel'].TestModel1s).to.be.eql([{testModel1Attribute: 'option_1'}, {testModel1Attribute: 'option_2'}]);
                done();
            }, 0);
        }, 0);
    });

    it("load() should load the form with an instance of mad.Model object", function () {
        var form = new mad.Form($form, {});
        form.start();

        // Add a textbox to the form.
        var $textbox = $('<input id="textbox" type="text"/>').appendTo($form);
        var textbox = new mad.form.Textbox($textbox, {
            modelReference: 'mad.test.model.TestModel.testModelAttribute'
        });
        form.addElement(textbox.start());

        // Add a checkbox to the form.
        var $checkbox = $('<div id="checkbox"></div>').appendTo($form);
        var checkbox = new mad.form.Checkbox($checkbox, {
            availableValues: {
                'option_1': 'Option 1',
                'option_2': 'Option 2',
                'option_3': 'Option 3'
            },
            modelReference: 'mad.test.model.TestModel.TestModel1s.testModel1Attribute'
        });
        form.addElement(checkbox.start());

        var testInstance = new mad.test.model.TestModel({
            testModelAttribute: 'test model attribute value',
            TestModel1s: new mad.test.model.TestModel1.List([{
                testModel1Attribute: 'option_1'
            }, {
                testModel1Attribute: 'option_2'
            }])
        });
        form.load(testInstance);

        var data = form.getData();
        expect(textbox.getValue()).to.be.equal('test model attribute value');
        expect(checkbox.getValue()).to.be.eql(['option_1', 'option_2']);
        expect(data['mad.test.model.TestModel'].testModelAttribute).to.be.not.null;
        expect(data['mad.test.model.TestModel'].TestModel1s).to.be.eql([{testModel1Attribute: 'option_1'}, {testModel1Attribute: 'option_2'}]);
    });

    it("validateElement() should validate an element", function() {
        var testModelValidationRules = mad.test.model.TestModel.validationRules;
        var form = new mad.Form($form, {});
        form.start();

        // Add a textbox to the form.
        var $textbox = $('<input id="textbox" type="text"/>').appendTo($form);
        var textbox = new mad.form.Textbox($textbox, {
            modelReference: 'mad.test.model.TestModel.testModelAttribute'
        });
        var $feedbackTxtBox = $('<span id="feedback_txtbox" for="textbox" />').appendTo($form);
        var feedbackTxtBox = new mad.form.Feedback($feedbackTxtBox, {});
        form.addElement(textbox.start(), feedbackTxtBox.start());

        // Add a checkbox to the form.
        var $checkbox = $('<div id="checkbox"></div>').appendTo($form);
        var checkbox = new mad.form.Checkbox($checkbox, {
            availableValues: {
                'option_1': 'Option 1',
                'option_2': 'Option 2',
                'option_3': 'Option 3'
            },
            modelReference: 'mad.test.model.TestModel.TestModel1s.testModel1Attribute'
        });
        form.addElement(checkbox.start());

        // The textbox can be empty.
        expect(form.validateElement(textbox)).to.be.true;
        // The checkbox can also be empty (@todo for now it is impossible to test the cardinality of a multiple model reference)
        expect(form.validateElement(checkbox)).to.be.true;

        // The textbox accept ASCII character
        textbox.setValue('ABCDE');
        expect(form.validateElement(textbox)).to.be.true;

        // The textbox doesn't accept special character.
        textbox.setValue('ABCDE&');
        expect(form.validateElement(textbox)).to.be.not.equal(true);
        expect($feedbackTxtBox.html()).to.contain(testModelValidationRules.testModelAttribute.alphaNumeric.message);

        // The textbox value length cannot be smaller than 3.
        textbox.setValue('AB');
        expect(form.validateElement(textbox)).to.be.not.equal(true);
        expect($feedbackTxtBox.html()).to.contain(testModelValidationRules.testModelAttribute.size.message);

        // The textbox value length cannot be smaller than 8.
        textbox.setValue('ABCDEFGHI');
        expect(form.validateElement(textbox)).to.be.not.equal(true);
        expect($feedbackTxtBox.html()).to.contain(testModelValidationRules.testModelAttribute.size.message);
    });

});
