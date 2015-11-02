import "test/bootstrap";
import "test/helper/model";
import "test/fixture/users";

describe("mad.Model", function () {

    it("should inherit can.Model", function () {
        var model = new mad.Model();
        expect(model).to.be.instanceOf(can.Model);
    });

    it("isModelAttribute() check if an attribute is a model attribute", function () {
        var MyModel = mad.Model.extend('mad.test.model.MyModel', {
            attributes: {
                AssociatedModel: 'mad.AssociatedModel.model',
                AssociatedModels: 'mad.AssociatedModel.models',
                classicAttribute: 'string'
            }
        }, {});
        expect(MyModel.isModelAttribute('AssociatedModel')).to.be.true;
        expect(MyModel.isModelAttribute('AssociatedModels')).to.be.true;
        expect(MyModel.isModelAttribute('classicAttribute')).to.be.false;
        delete mad.test.model.MyModel;
    });

    it("isMultipleAttribute() check if an attribute is a model attribute with a multiple cardinality", function () {
        var MyModel = mad.Model.extend('mad.test.model.MyModel', {
            attributes: {
                AssociatedModel: 'mad.AssociatedModel.model',
                AssociatedModels: 'mad.AssociatedModel.models',
                classicAttribute: 'string'
            }
        }, {});
        expect(MyModel.isMultipleAttribute('AssociatedModel')).to.be.false;
        expect(MyModel.isMultipleAttribute('AssociatedModels')).to.be.true;
        expect(MyModel.isMultipleAttribute('classicAttribute')).to.be.false;
        delete mad.test.model.MyModel;
    });

    it("getModelAttributes() extracts the model attributes from a string", function () {
        var attributes = [],
            instance = null;

        // Check simple model attribute
        attributes = mad.Model.getModelAttributes('mad.test.model.TestModel.testModelAttribute');

        expect(attributes[0].isMultiple()).to.be.false;
        expect(attributes[0].getName()).to.be.equal('mad.test.model.TestModel');
        instance = new (attributes[0].getModelReference())();
        expect(instance).to.be.instanceof(mad.test.model.TestModel);

        expect(attributes[1].isMultiple()).to.be.false;
        expect(attributes[1].getName()).to.be.equal('testModelAttribute');
        expect(attributes[1].getModelReference()).to.be.undefined;

        // Check nested model attribute
        attributes = mad.Model.getModelAttributes('mad.test.model.TestModel.TestModel1.myModel1Attribute');

        expect(attributes[0].isMultiple()).to.be.false;
        expect(attributes[0].getName()).to.be.equal('mad.test.model.TestModel');
        instance = new (attributes[0].getModelReference())();
        expect(instance).to.be.instanceof(mad.test.model.TestModel);

        expect(attributes[1].isMultiple()).to.be.false;
        expect(attributes[1].getName()).to.be.equal('TestModel1');
        instance = new (attributes[1].getModelReference())();
        expect(instance).to.be.instanceof(mad.test.model.TestModel1);

        expect(attributes[2].isMultiple()).to.be.false;
        expect(attributes[2].getName()).to.be.equal('myModel1Attribute');
        expect(attributes[2].getModelReference()).to.be.undefined;

        // Check nested models attribute
        attributes = mad.Model.getModelAttributes('mad.test.model.TestModel.TestModel1s.myModel1Attribute');

        expect(attributes[0].isMultiple()).to.be.false;
        expect(attributes[0].getName()).to.be.equal('mad.test.model.TestModel');
        instance = new (attributes[0].getModelReference())();
        expect(instance).to.be.instanceof(mad.test.model.TestModel);

        expect(attributes[1].isMultiple()).to.be.true;
        expect(attributes[1].getName()).to.be.equal('TestModel1s');
        instance = new (attributes[1].getModelReference())();
        expect(instance).to.be.instanceof(mad.test.model.TestModel1);

        expect(attributes[2].isMultiple()).to.be.false;
        expect(attributes[2].getName()).to.be.equal('myModel1Attribute');
        expect(attributes[2].getModelReference()).to.be.undefined;

        // Check multiple nested models attribute
        attributes = mad.Model.getModelAttributes('mad.test.model.TestModel.TestModel1.TestModel2.myModel2Attribute');

        expect(attributes[0].isMultiple()).to.be.false;
        expect(attributes[0].getName()).to.be.equal('mad.test.model.TestModel');
        instance = new (attributes[0].getModelReference())();
        expect(instance).to.be.instanceof(mad.test.model.TestModel);

        expect(attributes[1].isMultiple()).to.be.false;
        expect(attributes[1].getName()).to.be.equal('TestModel1');
        instance = new (attributes[1].getModelReference())();
        expect(instance).to.be.instanceof(mad.test.model.TestModel1);

        expect(attributes[2].isMultiple()).to.be.false;
        expect(attributes[2].getName()).to.be.equal('TestModel2');
        instance = new (attributes[2].getModelReference())();
        expect(instance).to.be.instanceof(mad.test.model.TestModel2);

        expect(attributes[3].isMultiple()).to.be.false;
        expect(attributes[3].getName()).to.be.equal('myModel2Attribute');
        expect(attributes[3].getModelReference()).to.be.undefined;
    });

    it("getModelAttributeValue() extracts the model attributes value of an instance from a string path", function () {
        var instance = new mad.test.model.TestModel({
            testModelAttribute: 'testModelAttributeValue',
            TestModel1: new mad.test.model.TestModel1({
                myModel1Attribute: 'myModel1AttributeValue'
            }),
            TestModel1s: new mad.test.model.TestModel1.List([{
                myModel1Attribute: 'myModel1sAttributeValue1'
            }, {
                myModel1Attribute: 'myModel1sAttributeValue2'
            }])
        }),
            value = null;

        // Test a simple value in a simple object.
        value = mad.Model.getModelAttributeValue('mad.test.model.TestModel.testModelAttribute', instance);
        expect(value).to.be.equal('testModelAttributeValue');

        // Test a simple value in a nested object.
        value = mad.Model.getModelAttributeValue('mad.test.model.TestModel.TestModel1.myModel1Attribute', instance);
        expect(value).to.be.equal('myModel1AttributeValue');

        // Test a multiple value in a nested object.
        value = mad.Model.getModelAttributeValue('mad.test.model.TestModel.TestModel1s.myModel1Attribute', instance);
        var expectedArray = ['myModel1sAttributeValue1', 'myModel1sAttributeValue2'];
        expect(_.difference(value, expectedArray)).to.be.empty;
    });

    it("validateAttribute() should validate an attribute regarding the defined validation rules", function () {
        var instance = new mad.test.model.TestModel({
            testModelAttribute: 'testModelAttributeValue',
            TestModel1: new mad.test.model.TestModel1({
                myModel1Attribute: 'myModel1AttributeValue'
            }),
            TestModel1s: new mad.test.model.TestModel1.List([{
                myModel1Attribute: 'myModel1sAttributeValue1'
            }, {
                myModel1Attribute: 'myModel1sAttributeValue2'
            }])
        }),
            value = null,
            isValid = null,
            testModelValidationRules = mad.test.model.TestModel.validationRules;

        //// The attribute can be empty.
        value = '';
        isValid = mad.test.model.TestModel.validateAttribute('testModelAttribute', value);
        expect(isValid).to.be.empty;

        // The attribute accept ASCII character.
        value = 'ABCDE';
        isValid = mad.test.model.TestModel.validateAttribute('testModelAttribute', value);
        expect(isValid).to.be.empty;

        // The textbox doesn't accept special character.
        value = 'ABCDE&';
        isValid = mad.test.model.TestModel.validateAttribute('testModelAttribute', value);
		expect(isValid).to.not.be.eql([]);
        expect(isValid[0]).to.contain(testModelValidationRules.testModelAttribute.alphaNumeric.message);

        // The textbox value length cannot be smaller than 3.
        value = 'AB';
        isValid = mad.test.model.TestModel.validateAttribute('testModelAttribute', value);
		expect(isValid).to.not.be.eql([]);
        expect(isValid[0]).to.contain(testModelValidationRules.testModelAttribute.size.message);

        // The textbox value length cannot be smaller than 3.
        value = 'ABCDEFGHI';
        isValid = mad.test.model.TestModel.validateAttribute('testModelAttribute', value);
        expect(isValid).to.not.be.eql([]);
        expect(isValid[0]).to.contain(testModelValidationRules.testModelAttribute.size.message);
    });

    it("model's instances should be updated when findAll retrieves updated instances", function(done) {
        // It'll be used to store the list of users.
        var list = new can.List(),
            updatedEventCount = 0,
            updatedTarget = null;

        // Listen to changes on the list.
        list.bind('change', function(change) {
            updatedEventCount ++;
            updatedTarget = change.target;
        });

        mad.test.model.UserTestModel.findAll().then(function(data) {
            // Store the retrieved instances in a list.
            list.push.apply(list, data);

            // A change on the list should have been caught.
            expect(updatedEventCount).to.be.equal(1);
            expect(updatedTarget.length).to.be.equal(3);

            // Check that all elements have been retrieved.
            expect(mad.model.List.indexOf(list, '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce')).to.be.not.equal(-1);
            expect(mad.model.List.indexOf(list, '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce')).to.be.not.equal(-1);
            expect(mad.model.List.indexOf(list, 'bbd56042-c5cd-11e1-a0c5-080027796c4e')).to.be.not.equal(-1);

            // Check that all elements are well instances of mad.test.model.UserTestModel
            list.each(function(el){
                expect(el).to.be.instanceOf(mad.test.model.UserTestModel);
            });

            // Check that the findAll update well the instances which are already in use by others.
            mad.test.model.UserTestModel.findAll({url:'/testuserscarolupdated'}).then(function(data) {
                // A change on the list should have been caught.
                expect(updatedEventCount).to.be.equal(2);
                expect(updatedTarget.id).to.be.equal('50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce');

                // Carol should have an updated email.
                var index = mad.model.List.indexOf(list, '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce'),
                    instance = list[index],
                    serverInstanceIndex = mad.model.List.indexOf(data, '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce'),
                    serverInstance = data[serverInstanceIndex];

                // Check that the instance of carol returned by the server has an updated email.
                expect(serverInstance.email).to.be.equal('carol_updated_email@passbolt.com');
                // Check that the instance of carol stored in the list has well been updated.
                expect(instance.email).to.be.equal('carol_updated_email@passbolt.com');
                done();
            });
        }).fail(function(data) {
            expect(false).to.be.true;
        });
    });

    it("model's instances should be updated when findOne retrieves updated instances", function(done) {
        // It'll be used to store the list of users.
        var list = new can.List(),
            updatedEventCount = 0,
            updatedTarget = null;

        // Listen to changes on the list.
        list.bind('change', function(change) {
            updatedEventCount ++;
            updatedTarget = change.target;
        });

        mad.test.model.UserTestModel.findAll().then(function(data) {
            // Store the retrieved instances in a list.
            list.push.apply(list, data);

            // A change on the list should have been caught.
            expect(updatedEventCount).to.be.equal(1);
            expect(updatedTarget.length).to.be.equal(3);

            // Check that all elements have been retrieved.
            expect(mad.model.List.indexOf(list, '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce')).to.be.not.equal(-1);
            expect(mad.model.List.indexOf(list, '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce')).to.be.not.equal(-1);
            expect(mad.model.List.indexOf(list, 'bbd56042-c5cd-11e1-a0c5-080027796c4e')).to.be.not.equal(-1);

            // Check that all elements are well instances of mad.test.model.UserTestModel
            list.each(function(el){
                expect(el).to.be.instanceOf(mad.test.model.UserTestModel);
            });

            // Check that the findAll update well the instances which are already in use by others.
            mad.test.model.UserTestModel.findOne({url:'/testusersupdated/{id}', id:'50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce'}).then(function(data) {
                // A change on the list should have been caught.
                expect(updatedEventCount).to.be.equal(2);
                expect(updatedTarget.id).to.be.equal('50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce');

                // Carol should have an updated email.
                var index = mad.model.List.indexOf(list, '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce'),
                    instance = list[index];

                // Check that the instance of carol returned by the server has an updated email.
                expect(data.email).to.be.equal('carol_updated_email@passbolt.com');
                // Check that the instance of carol stored in the list has well been updated.
                expect(instance.email).to.be.equal('carol_updated_email@passbolt.com');
                done();
            });

        }).fail(function(data) {
            expect(false).to.be.true;
        });

    });

    it("model's instances should be updated when an instance is updated.", function(done) {
        // It'll be used to store the list of users.
        var list = new can.List(),
            updatedEventCount = 0,
            updatedTarget = null;

        // Listen to changes on the list.
        list.bind('change', function(change) {
            updatedEventCount ++;
            updatedTarget = change.target;
        });

        mad.test.model.UserTestModel.findAll().then(function(data) {
            // Store the retrieved instances in a list.
            list.push.apply(list, data);

            // A change on the list should have been caught.
            expect(updatedEventCount).to.be.equal(1);
            expect(updatedTarget.length).to.be.equal(3);

            // Check that all elements have been retrieved.
            expect(mad.model.List.indexOf(list, '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce')).to.be.not.equal(-1);
            expect(mad.model.List.indexOf(list, '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce')).to.be.not.equal(-1);
            expect(mad.model.List.indexOf(list, 'bbd56042-c5cd-11e1-a0c5-080027796c4e')).to.be.not.equal(-1);

            // Check that all elements are well instances of mad.test.model.UserTestModel
            list.each(function(el){
                expect(el).to.be.instanceOf(mad.test.model.UserTestModel);
            });

            // Retrieve the carol instance.
            var index = mad.model.List.indexOf(list, '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce'),
                instance = list[index];

            // Update the email attribute, and check that the event is bound.
            // Canjs triggers an event when an attribute of an instance is changed.
            instance.attr('email', 'carol_updated_email_from_update_func@passbolt.com');

            expect(updatedEventCount).to.be.equal(2);
            expect(updatedTarget.id).to.be.equal('50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce');

            instance.save().then(function(data) {
                // A change on the list should have been caught.
                expect(updatedEventCount).to.be.equal(3);
                expect(updatedTarget.id).to.be.equal('50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce');
                done();
            });
        }).fail(function(data) {
            expect(false).to.be.true;
        });

    });

    it("model's instances should be updated when an instance is retrieved with a custom find function and the recieved data contain a change.", function(done) {
        // It'll be used to store the list of users.
        var list = new can.List(),
            updatedEventCount = 0,
            updatedTarget = null;

        // Listen to changes on the list.
        list.bind('change', function(change) {
            updatedEventCount ++;
            updatedTarget = change.target;
        });

        mad.test.model.UserTestModel.findAll().then(function(data) {
            // Store the retrieved instances in a list.
            list.push.apply(list, data);

            // A change on the list should have been caught.
            expect(updatedEventCount).to.be.equal(1);
            expect(updatedTarget.length).to.be.equal(3);

            // Check that all elements have been retrieved.
            expect(mad.model.List.indexOf(list, '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce')).to.be.not.equal(-1);
            expect(mad.model.List.indexOf(list, '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce')).to.be.not.equal(-1);
            expect(mad.model.List.indexOf(list, 'bbd56042-c5cd-11e1-a0c5-080027796c4e')).to.be.not.equal(-1);

            // Check that all elements are well instances of mad.test.model.UserTestModel
            list.each(function(el){
                expect(el).to.be.instanceOf(mad.test.model.UserTestModel);
            });

            // Retrieve the carol instance with a custom find function.
            mad.test.model.UserTestModel.findCustom().then(function (data) {
                // A change on the list should have been caught.
                expect(updatedEventCount).to.be.equal(2);
                expect(updatedTarget.id).to.be.equal('50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce');
                done();
            });

        }).fail(function(data) {
            expect(false).to.be.true;
        });

    });
});
