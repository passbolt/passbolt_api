steal(
	'funcunit',
	'mad/test/view/template/componentController.ejs'
).then(function () {

	var testEnv = null;
	module("mad.helper.component", {
		// runs before each test
		setup: function () {
			stop();

			S.open('//' + 'mad/test/testEnv/app.html', function () {
				// store the env windows in a global var for the following unit tests
				testEnv = S.win;
				// when the app is ready continue the tests
				S('body').hasClass('mad_test_app_ready', true, function () {
					start();
				});
			});
		},
		// runs after each test
		teardown: function () {}
	});

	/* *****************************************************************************
	 * 
	 **************************************************************************** */

	test('helper.component.BoxDecorator : Decorate a component with this box decorator', function () {
		testEnv.mad.controller.ComponentController.extend('mad.test.controller.ComponentController', {}, {});

		//add the component container to the view
		var componentId = 'mad_test_componentToDecorate';
		testEnv.mad.app.element.append('<div id="' + componentId + '"/>');
		var $component = testEnv.$('#' + componentId);

		//create the component
		var component = new testEnv.mad.test.controller.ComponentController($component);
		ok(typeof component != 'undefined', 'The component has well been instanciated');

		//decorate and render the component
		component.decorate('mad.helper.component.BoxDecorator');
		ok(typeof component.render != 'undefined', 'The component has well been decorated');
		ok(typeof component.getBoxElement != 'undefined', 'The component has well been decorated');

		component.setTemplateUri('//' + 'mad/test/view/template/componentController.ejs');
		component.render();

		// the component has well been rendered
		ok(testEnv.$('#' + componentId).length != 0, 'The component controller has well been rendered');

		// the element is always the element referenced by the id mad-test-component_to_decorate
		equal(component.element[0].id, componentId, 'The html element which is associated to the component is always the element with the id ' + componentId);
		equal(component.getId(), componentId, 'The html element which is associated to the component is always the element with the id ' + componentId);
		// The element is well referenced in the app
		ok(testEnv.mad.app.getComponent(componentId) != null, 'A component exist for the component id');
		equal(testEnv.mad.app.getComponent(componentId).getId(), componentId, 'The component has well been referenced');

		// The box container has been weel rendered
		ok(testEnv.$('.mad_helper_component_boxDecorator').length != 0, 'The boxDecorator container has well been rendered');
		ok(testEnv.$('.mad_helper_component_boxDecorator_content').length != 0, 'The boxDecorator content container has well been rendered');

		var $boxElement = component.getBoxElement();
		ok($boxElement.hasClass('mad_helper_component_boxDecorator'), 'The box element returned by the function getBoxElement own the right class');

		// remove the box element, and check that the component is well removed
		$boxElement.remove();

		// Check that the component is not existing anymore
		ok(testEnv.mad.app.getComponent(componentId) == null, 'The component has well been unreferenced when it was deleted');
		equal(testEnv.$('#' + componentId).length, 0, 'The component controller has well been removed');
	});
});