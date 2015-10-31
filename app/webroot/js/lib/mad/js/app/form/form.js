import $ from "jquery";
import mad from "mad/mad";
import "mad/form/form";
import "mad/form/element/textbox";
import "mad/form/element/checkbox";
import "test/helper/model";

var form = new mad.Form($('#form'), {
    callbacks: {
        submit: function(data) {
            console.log(data);
        }
    }
});
form.start();

// Add category id hidden field
form.addElement(
    new mad.form.Textbox($('#js_test_model_attribute'), {
        modelReference: 'mad.test.model.TestModel.testModelAttribute',
        validate: true
    }).start(),
    new mad.form.Feedback($('#js_test_model_attribute_feedback'), {}).start()
);

// Add category id hidden field
form.addElement(
    new mad.form.Textbox($('#js_test_model1_attribute'), {
        modelReference: 'mad.test.model.TestModel.TestModel1.testModel1Attribute',
        validate: true
    }).start(),
    new mad.form.Feedback($('#js_test_model1_attribute_feedback'), {}).start()
);

// Add category id hidden field
form.addElement(
    new mad.form.Checkbox($('#js_test_model2s'), {
        modelReference: 'mad.test.model.TestModel.TestModel1.TestModel2s.testModel2Attribute',
        validate: true,
        availableValues: {
            'option_1': 'Option 1',
            'option_2': 'Option 2',
            'option_3': 'Option 3'
        }
    }).start(),
    new mad.form.Feedback($('#js_test_model2s_feedback'), {}).start()
);
