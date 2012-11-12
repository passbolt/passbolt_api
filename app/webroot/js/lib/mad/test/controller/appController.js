steal('funcunit', function () {

	module("mad.controller", {
		// runs before each test
		setup: function () {},
		// runs after each test
		teardown: function () {}
	});

	test('AppController : Set the namespace of the application', function () {
		// try to get the app controller before the application namespace has been instantiated
//		raises(function () {
//			var appCtl = mad.controller.AppController.singleton($appCtl);
//		}, mad.error.Exception, 'The application namespace is not initialized');
		// try to access to the globals function before the initialization of the ns
		raises(function () {
			mad.controller.AppController.getGlobal('TEST');
		}, mad.error.Exception, 'The application namespace is not initialized');

		mad.controller.AppController.setNs('APP_NS_ID_APPCTLTEST');
		equal('APP_NS_ID_APPCTLTEST', mad.controller.AppController.APP_NS_ID, 'Test the static var APP_NS_ID of the class mad.controller.AppController.');
		ok(typeof window['APP_NS_ID_APPCTLTEST'] != 'undefined', 'The namespace (APP_NS_ID_APPCTLTEST) has well been instanciated as a global var.');

		// If the namespace has yet been instanciated and populated, it will throw an error
		raises(function () {
			mad.controller.AppController.setNs('APP_NS_ID_APPCTLTEST');
		}, mad.error.Exception, 'The application namespace has yet been initialized');

		equal(mad.controller.AppController.getGlobal('APP_NS_ID'), 'APP_NS_ID_APPCTLTEST', 'The global variable APP_NS_ID_APPCTLTEST has well been stored');
		equal(mad.controller.AppController.getGlobal('APP_NS').APP_NS_ID, 'APP_NS_ID_APPCTLTEST', 'The namespace APP_NS has well been stored in the global variable');

		// check the alias on mad
		equal(mad.getGlobal('APP_NS_ID'), 'APP_NS_ID_APPCTLTEST', 'The alias mad.APP_NS_ID is ok');
		equal(mad.getGlobal('APP_NS').APP_NS_ID, 'APP_NS_ID_APPCTLTEST', 'The alias mad.APP_NS_ID is ok');

		// check the alias on the app namespace
		equal(mad.controller.AppController.getGlobal('APP_NS').getGlobal('APP_NS_ID'), 'APP_NS_ID_APPCTLTEST', 'The alias mad.APP_NS_ID is ok');
		equal(mad.controller.AppController.getGlobal('APP_NS').getGlobal('APP_NS').APP_NS_ID, 'APP_NS_ID_APPCTLTEST', 'The alias mad.APP_NS_ID is ok');
		equal(APP_NS_ID_APPCTLTEST.getGlobal('APP_NS').getGlobal('APP_NS_ID'), 'APP_NS_ID_APPCTLTEST', 'The alias mad.APP_NS_ID is ok');
		equal(APP_NS_ID_APPCTLTEST.getGlobal('APP_NS').getGlobal('APP_NS').APP_NS_ID, 'APP_NS_ID_APPCTLTEST', 'The alias mad.APP_NS_ID is ok');
	});

//	test('AppController : The app controller is a singleton', function () {
//		// try to instancy directly the app controller
//		raises(function () {
//			new mad.controller.AppController();
//		}, mad.error.CallPrivateFunctionException, mad.error.CallPrivateFunctionException.message);
//
//		var $appCtl = $('body').append('<div id="app_ctl"/>');
//		var appCtl = mad.controller.AppController.singleton($appCtl);
//		ok(appCtl instanceof mad.controller.AppController, 'The app controller is an instance of the class mad.controller.AppController');
//	});

//	test('AppController : Reference components', function () {
//		var appCtl = mad.controller.AppController.singleton();
//		mad.controller.ComponentController.extend('mad.controller.component.UnitTestComponentController', {}, {});
//
//		var $component1 = appCtl.element.append('<div id="component1"/>');
//		var component = new mad.controller.component.UnitTestComponentController($component1)
//
//		ok(mad.app.getComponent(component.getId()) != null, 'A component exist for the component id');
//		ok(mad.app.getComponent(component.getId()).getId() == component.getId(), 'The component has well been referenced');
//
//		var componentId = component.getId();
//		component.destroy();
//		ok(mad.app.getComponent(componentId) == null, 'The component has well been unreferenced when it was deleterd');
//
//		delete mad.controller.component.UnitTestComponentController;
//	});

	test('AppController : Reset the application controller', function () {
		mad.controller.AppController.destroy();
		ok(typeof APP_NS_ID_APPCTLTEST == 'undefined', 'The global application has well been deleted');
		ok(mad.controller.AppController.length == 0, 'All the global variables have well been deleted');
		ok(mad.controller.AppController.APP_NS_ID == null, 'The namespace has well been deleted in the app controller singleton');
	});

});