steal('funcunit').then(function () {

	var testEnv = null;
	module("mad.controller", {
		// runs before each test
		setup: function () {
			stop();
			var url = '//lib/mad/test/testEnv/app.html';
//			var url = steal.idToUri('mad/test/testEnv/app.html').toString(); // sopen does not get full url, it needs relative url
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

	test('ComponentController : Instanciation', function () {
		testEnv.mad.controller.ComponentController.extend('mad.test.controller.ComponentController', {}, {});

		var $componentController = testEnv.$('<div id="componentController"/>').appendTo('body');
		S('#componentController').exists(1000, null, 'The container div has well been rendered');

		var componentController = new testEnv.mad.test.controller.ComponentController($componentController);
		ok(componentController instanceof testEnv.mad.test.controller.ComponentController, 'The instanciated component is an instance of the right type');

		componentController.setTemplateUri('mad/test/view/template/componentController.ejs');
		componentController.render();
		S('#componentControllerContent').exists(1000, null, 'The component template has well been rendered');
		S('#componentControllerContent').text('A Simple Component Controller Content', 1000, null, 'The component template has well been rendered, double check');
	});

});