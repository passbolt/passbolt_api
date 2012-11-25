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
	});

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

		// Try to remove an element which is not referenced by the form controller
		raises(function () {
			form.removeElement(element);
		});
	});

	test('FormController : getData', function () {
		var form = instanciateForm();
		var element = testEnv.mad.helper.ComponentHelper.create(form.element, 'last', testEnv.mad.form.element.TextboxController, {
			'modelReference': 'demo.model.Person.surname',
			'value': 'surname'
		});
		form.addElement(element);
		var element = testEnv.mad.helper.ComponentHelper.create(form.element, 'last', testEnv.mad.form.element.TextboxController, {
			'modelReference': 'demo.model.Person.name',
			'value': 'name'
		});
		form.addElement(element);
		var element = testEnv.mad.helper.ComponentHelper.create(form.element, 'last', testEnv.mad.form.element.TextboxController, {
			'modelReference': 'demo.model.Person.freelancer',
			'availableValues': {
				'0': 'No',
				'1': 'Yes'
			},
			'value': true
		});
		form.addElement(element);
		var element = testEnv.mad.helper.ComponentHelper.create(form.element, 'last', testEnv.mad.form.element.TextboxController, {
			'modelReference': 'demo.model.Person.phone',
			'value': '03873298'
		});
		form.addElement(element);
		var element = testEnv.mad.helper.ComponentHelper.create(form.element, 'last', testEnv.mad.form.element.TextboxController, {
			'modelReference': 'demo.model.Person.email',
			'value': 'email@email.com'
		});
		form.addElement(element);
		// get all available countries
		var availableCountries = {};
		testEnv.demo.model.Country.findAll({}, function (countries, response, request) {
			can.each(countries, function (country, i) {
				availableCountries[country.id] = country.label;
			});
		});
		var element = testEnv.mad.helper.ComponentHelper.create(form.element, 'last', testEnv.mad.form.element.CheckboxController, {
			'modelReference': 'demo.model.Person.visited.id',
			'availableValues': availableCountries,
			'value': ['belgium', 'argentina']
		});
		form.addElement(element);
		
		var data = form.getData();
		ok(typeof data['demo.model.Person'] != 'undefined', 'The namespace demo.model.Person is well returned');
		var person = data['demo.model.Person'];
		equal(person.surname, 'surname', 'The value of the field surname is well returned');
		equal(person.name, 'name', 'The value of the field name is well returned');
		equal(person.freelancer, true, 'The value of the field freelancer is well returned');
		equal(person.phone, '03873298', 'The value of the field phone is well returned');
		equal(person.email, 'email@email.com', 'The value of the field email is well returned');
		ok($.isArray(person.visited), 'The value of the visited field is well an array');
		equal(person.visited.length, 2, 'The visited field is well containing 2 values');
		equal(person.visited[0].id, 'belgium', 'The visited field contain well the belgium country');
		equal(person.visited[1].id, 'argentina', 'The visited field contain well the argentina country');
	});

});