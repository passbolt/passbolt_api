steal('funcunit', function(){

	var testEnv = null;
	module("mad.helper.component", {
		// runs before each test
		setup: function(){
			S.open('./testEnv/app.html', function(){
				// store the env windows in a global var for the following unit tests
				testEnv = S.win;
			});
		},
		// runs after each test
		teardown: function(){
		}
	});

	/* *****************************************************************************
	* 
	**************************************************************************** */

	test('helper.component.BoxDecorator : Decorate a component with this box decorator', function(){
		testEnv.mad.controller.ComponentController.extend('mad.test.controller.ComponentController',
		{
			'init': function()
			{
				this.options.template = '//'+MAD_ROOT+'/test/helper/component/view/component.ejs';
				this._super();
			}
		});

		//add the component container to the view
		var $component = testEnv.$('<div id="mad-test-component_to_decorate"/>').appendTo(testEnv.mad.app.element);

		//create the component
		var component = new testEnv.mad.test.controller.ComponentController($component);
		//decorate and render the component
		component
			.decorate('mad.helper.component.BoxDecorator')
			.render();

		// the element is always the element referenced by the id mad-test-component_to_decorate
		equal (component.element[0].id, 'mad-test-component_to_decorate', 'The html element which is associated to the component is always the element with the id mad-test-component_to_decorate');
		equal (component.getId(), 'mad-test-component_to_decorate', 'The html element which is associated to the component is always the element with the id mad-test-component_to_decorate');

		// The element is well referenced in the app
		ok(testEnv.mad.app.getComponent(component.getId())!=null, 'A component exist for the component id');
		ok(testEnv.mad.app.getComponent(component.getId()).getId()==component.getId(), 'The component has well been referenced');

		//check the component is well contained by the box
		equal(component.boxElement().attr('id'), 'mad-helper-component-box_decorator', 'The box container is well returned');
		ok(component.boxElement().find('#'+component.getId()).length != 0, 'The box container contains the component');

		// remove the component
		var componentId = component.getId();
		component.boxElement().remove();

		// the component is not existing anymore
		ok(testEnv.mad.app.getComponent(componentId)==null, 'The component has well been unreferenced when it was deleted');

	});
});