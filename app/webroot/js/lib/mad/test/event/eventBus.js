steal(
	'funcunit'
).then(function () {

	var testEnv = null;
	module("mad.form", {
		// runs before each test
		setup: function () {
			var boot = new mad.bootstrap.AppBootstrap({
				'config': [
					'mad/test/testEnv/config_light.json'
				]
			});
		},
		// runs after each test
		teardown: function () {
		}
	});

	function instanciateEventBus () {
		return mad.helper.ComponentHelper.create($('body'), 'last', mad.event.EventBus, {});
	}

	test('EventBus: instanciate', function () {
		var eventBus = instanciateEventBus();
		ok(eventBus instanceof mad.event.EventBus, 'The event bus is well and instance of the mad.event.EventBus');
	});

	test('EventBus: trigger & listen', function () {
		var bus = instanciateEventBus();

		// test simple event
		bus.bind('event1', function () {
			ok(true, 'The event bus well catched the event mad_test_event_event1');
			bus.element.remove();
			start();
		});
		bus.trigger('event1');
	});

});