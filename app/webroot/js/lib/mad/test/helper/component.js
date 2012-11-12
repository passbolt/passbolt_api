steal('funcunit').then(function () {

	var testEnv = null;
	module("mad.helper", {
		// runs before each test
		setup: function () {
			stop();
			var url = '//lib/mad/test/testEnv/app.html';
//			var url = steal.idToUri('mad/test/testEnv/app.html').toString(); // sopen does not get full url, it needs relative url
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
		var refElement = S('#mad_test_app_controller'),
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
//			mad.helper.ComponentHelper.create($('#refElement'), position, $.Class, {id: uid});
//		}, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
	});

	test('ComponentHelper : create', function () {
		var refElement = S('#mad_test_app_controller'),
			position = 'inside_replace',
			uid = "uid",
			component = null,
			Clazz = testEnv.mad.test.controller.ComponentController;

		component = mad.helper.ComponentHelper.create(refElement, position, Clazz, {id: uid});
		S('#' + uid).exists(1000, null, 'The component controller has been rendered');
		equal(testEnv.jQuery('#' + uid).controller() instanceof Clazz, true, 'The controller is well associated to the html node');
	});

});