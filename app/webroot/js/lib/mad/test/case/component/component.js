import "test/bootstrap";

describe("mad.Component", function(){

	// Extend mad.Component for the needs of the tests.
	var MyComponent = mad.Component.extend('MyComponent', {
		'defaults': {
			'templateBased': false
		}
	}, {});

	it("should inherit can.Control & mad.Control", function(){
		var component = new mad.Component($('#test-html'));

		// Basic control of classes inheritance.
		expect(component).to.be.instanceOf(can.Control);
		expect(component).to.be.instanceOf(mad.Control);
		expect(component).to.be.instanceOf(mad.Component);

		component.destroy();
	});

	it("should be instantiated with the right properties & values", function(){
		var component = new MyComponent($('#test-html')),
			$elt = $(component.element);

		// The state property should be empty.
		expect(component.state.previous.length).to.be.equal(0);
		expect(component.state.current.length).to.be.equal(0);

		// The associated HTML Element should have the Component fullName as css class.
		expect($elt.hasClass('my_component')).to.be.true;
		// The associated HTML Element should have the Component optional cssClasses as css classes.
		expect($elt.hasClass('js_component')).to.be.true;

		component.destroy();

		// After destroy, the associated HTML Element should not have the Component fullName as css class.
		expect($elt.hasClass('my_component')).to.be.false;
		// After destroy, the associated HTML Element should not have the Component optional cssClasses as css classes.
		expect($elt.hasClass('js_component')).to.be.false;
	});

	it("should be in the default state after being started", function() {
		var component = new MyComponent($('#test-html')),
			$elt = $(component.element);

		component.start();

		// After start, the component' state is initialized with the Component default option.
		expect(component.state.is('loading')).to.be.false;
		expect(component.state.is('ready')).to.be.true;

		// After start, the associated HTML Element should have the Component current state name as css class.
		expect($elt.hasClass('ready')).to.be.true;

		component.destroy();

		// After destroy, the associated HTML Element should not have the Component current state name as css class.
		expect($elt.hasClass('ready')).to.be.false;
	});

	it("should be in the overridden default state after being started", function() {
		var MyComponentOverriddenState = mad.Component.extend('MyComponentOverriddenState', {
			'defaults': {
				'state': 'disabled',
				'templateBased': false
			}
		}, {});

		var component = new MyComponentOverriddenState($('#test-html'));

		component.start();

		// After start, the component' state is initialized with the Component overriden option.
		expect(component.state.is('loading')).to.be.false;
		expect(component.state.is('ready')).to.be.false;
		expect(component.state.is('disabled')).to.be.true;

		component.destroy();
	});

	it("should be rendered based on a custom template if defined", function() {
		var MyComponentOverriddenTemplate = mad.Component.extend('MyComponentOverriddenTemplate', {
			'defaults': {
				'state': 'disabled',
				'templateUri': 'test/case/component/my_component.tpl'
			}
		}, {});

		var component = new MyComponentOverriddenTemplate($('#test-html')),
			$elt = $(component.element);

		component.start();

		// I should see a trace of the rendered custom template on the page.
		expect($elt.text()).to.contain('look this is my custom template');

		$elt.empty();
		component.destroy();
	});
});
