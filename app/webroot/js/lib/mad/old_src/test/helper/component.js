steal('funcunit').then(function () {

	var testEnv = null;
	module("mad.helper", {
		// runs before each test
		setup: function () {
			stop();
			var url = 'lib/mad/test/testEnv/app.html';
			S.open(url, function () {
				// store the env windows in a global var for the following unit tests
				testEnv = S.win;
				testEnv.mad.controller.ComponentController.extend('mad.test.controller.ComponentController', {}, {});
				S('body').hasClass('mad_test_app_ready', true, function () {
					start();
				});
			});
		},
		// runs after each test
		teardown: function () {}
	});

	test('ComponentHelper : create exception', function () {
		var refElement = testEnv.mad.app.element,
			position = 'inside_replace',
			uid = "uid",
			Clazz = testEnv.mad.controller.ComponentController;

		// refElement string, element not found
		raises(function () {
			mad.helper.ComponentHelper.create('refElement', position, Clazz, {id: uid});
		}, mad.error.WrongParametersException, mad.error.WrongParametersException.message);

		// refElement jquery, element not found
		raises(function () {
			mad.helper.ComponentHelper.create($('#refElement'), position, Clazz, {id: uid});
		}, mad.error.WrongParametersException, mad.error.WrongParametersException.message);

		// The class reference is not a Component Controller
//		raises(function () {
//			mad.helper.ComponentHelper.create($('#refElement'), position, can.Construct, {id: uid});
//		}, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
	});

	test('ComponentHelper : create', function () {
		var refElement = testEnv.mad.app.element,
			position = 'inside_replace',
			uid = "uid",
			Clazz = testEnv.mad.test.controller.ComponentController,
			component = null;

		component = testEnv.mad.helper.ComponentHelper.create(refElement, position, Clazz, {id: uid});
		S('#' + uid).exists(1000, null, 'The component controller has been rendered');
		equal(testEnv.jQuery('#' + uid).controller() instanceof Clazz, true, 'The controller is well associated to the html node');
	});

});