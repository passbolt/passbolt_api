steal('funcunit', function () {

	module("mad.helper", {

		// runs before each test
		setup: function () {
			mad.controller.AppController.destroy();
			mad.controller.AppController.setNs(APP_NS_ID);
		},
		// runs after each test
		teardown: function () {
			mad.controller.AppController.destroy();
		}
	});

	/* *****************************************************************************
	 * Test the function get view patg
	 **************************************************************************** */

	test('ControllerHelper.getViewPath : get view path of mad controllers', function () {
		mad.controller.Controller.extend('mad.controller.UnitTestController', {}, {});
		mad.controller.Controller.extend('mad.controler.UnitTestController', {}, {}); // Controller class name mal formed
		mad.controller.Controller.extend('mad.controller.component.UnitTestComponentController', {}, {});
		mad.controller.Controller.extend('mad.controler.component.UnitTestComponentController', {}, {}); // Controller class name mal formed
		mad.controller.Controller.extend('mad.controller.component.UnitTestComponentControler', {}, {}); // Controller class name mal formed
		equal(mad.helper.ControllerHelper.getViewPath(mad.controller.UnitTestController), '//' + 'mad/view/template/unitTest.ejs', 'Get view path ok with controller in the controller folder of the mad lib');

		equal(mad.helper.ControllerHelper.getViewPath(mad.controller.component.UnitTestComponentController), '//' + 'mad/view/template/component/unitTestComponent.ejs', 'Get view path ok with controller in a subfolder of the controller folder of the mad lib');

		// Try to get a view path of an undefined class
		raises(function () {
			mad.helper.ControllerHelper.getViewPath(mad.controler.UnitTestController);
		}, Error);


		raises(function () {
			mad.helper.ControllerHelper.getViewPath(mad.controller.component.UnitTestComponentControler);
		}, mad.error.Exception, mad.error.Exception.message);
	});

	test('ControllerHelper.getViewPath : get view path of app controllers', function () {
		mad.controller.Controller.extend(mad.APP_NS_ID + '.controller.UnitTestController', {}, {});
		mad.controller.Controller.extend(mad.APP_NS_ID + '.controller.component.UnitTestComponentController', {}, {});

		equal(mad.helper.ControllerHelper.getViewPath(mad.APP_NS.controller.UnitTestController), '//app/view/template/unitTest.ejs', 'Get view path ok with controller in the controller folder of the app');

		equal(mad.helper.ControllerHelper.getViewPath(mad.APP_NS.controller.component.UnitTestComponentController), '//app/view/template/component/unitTestComponent.ejs', 'Get view path ok with controller in a subfolder of the controller folder of the app');
	});

	test('ControllerHelper.getViewPath : get view path of plugin controllers', function () {
		mad.controller.Controller.extend(mad.APP_NS_ID + '.plugin1.controller.UnitTestController', {}, {});
		mad.controller.Controller.extend(mad.APP_NS_ID + '.plugin1.controller.component.UnitTestComponentController', {}, {});

		equal(mad.helper.ControllerHelper.getViewPath(mad.APP_NS.plugin1.controller.UnitTestController), '//plugin/plugin1/view/template/unitTest.ejs', 'Get view path ok with controller in the controller folder of the plugin1');

		equal(mad.helper.ControllerHelper.getViewPath(mad.APP_NS.plugin1.controller.component.UnitTestComponentController), '//plugin/plugin1/view/template/component/unitTestComponent.ejs', 'Get view path ok with controller in a subfolder of the controller folder of the plugin1');
	});
});