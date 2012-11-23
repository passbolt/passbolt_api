steal(
	'funcunit'
).then(function () {

	var testEnv = null;
	module("mad.form", {
		// runs before each test
		setup: function () {
			stop();
			var url = '//lib/mad/test/testEnv/app.html';
			S.open(url, function () {
				// store the env windows in a global var for the following unit tests
				testEnv = S.win;
				S('body').hasClass('mad_test_app_ready', true, function () {
					start();
				});
			});
		},
		// runs after each test
		teardown: function () {}
	})

	function instanciateForm () {
		var returnValue = testEnv.mad.helper.ComponentHelper.create(testEnv.mad.app.element, 'last', testEnv.mad.form.FormController, {});
		return returnValue;
	}

	test('FormController : Instanciation', function () {
		var form = instanciateForm();
		ok(form instanceof testEnv.mad.form.FormController, 'The instanciated form is an instance of the right type');
	});

	test('FormController : addElement', function () {
		var form = instanciateForm();

		// Add a non mad.form.Element instance to the form
		raises(function () {
			var element = testEnv.mad.helper.ComponentHelper.create(form.element, 'last', testEnv.mad.controller.ComponentController, {
				modelReference: 'passbolt.model.Category.parent_id'
			});
			form.addElement(element);
		}, testEnv.mad.error.WrongParametersException, testEnv.mad.error.WrongParametersException.message);

		// Try to add all kind of form elements
		for (var i in testEnv.mad.form.element){
			var element = testEnv.mad.helper.ComponentHelper.create(form.element, 'last', testEnv.mad.form.element[i], {});
			ok(element instanceof testEnv.mad.form.element[i], 'The form element is an instance of the right type');
			form.addElement(element);
			ok(typeof form.elements[element.getId()] != 'undefined', 'The form element [' + i + '] has been added to the form');
		}
		
		equal(form.elements.length, testEnv.mad.form.element.length, 'The form contains all the expected elements');
	});

	test('FormController : removeElement', function () {
		var form = instanciateForm();
		var element = testEnv.mad.helper.ComponentHelper.create(form.element, 'last', testEnv.mad.form.element.TextboxController, {});
		form.addElement(element);

		// Try to remove null
		raises(function () {
			form.removeElement(null);
		}, testEnv.mad.error.WrongParametersException, testEnv.mad.error.WrongParametersException.message);

		// Try to remove an element which not belongs to mad.form.FormElement
		raises(function () {
			var elementBidon = new testEnv.mad.model.Model();
			form.removeElement(elementBidon);
		}, testEnv.mad.error.WrongParametersException, testEnv.mad.error.WrongParametersException.message);

		form.removeElement(element);
		ok($.isEmptyObject(form.elements), 'The form contains all the expected elements');

		// Try to remove an element which not belongs to mad.form.FormElement
		raises(function () {
			form.removeElement(element);
		});
	});

});