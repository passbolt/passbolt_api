steal(
	'funcunit'
).then(function () {

	var testEnv = null;
	module("mad.form", {
		// runs before each test
		setup: function () {
			stop();
			var url = '//lib/mad/test/testEnv/mad.html';
			S.open(url, function () {
				// store the env windows in a global var for the following unit tests
				testEnv = S.win;
				start();
			});
		},
		// runs after each test
		teardown: function () {}
	});

	function instanciateEventBud () {
		return testEnv.mad.helper.ComponentHelper.create(testEnv.$('#mad_test_app_controller'), 'last', testEnv.mad.event.EventBus, {});
	}

	test('EventBus: instanciate', function () {
		var eventBus = instanciateEventBud();
		ok(eventBus instanceof testEnv.mad.event.EventBus, 'The event bus is well and instance of the mad.event.EventBus');
	});

	test('EventBus: trigger & listen', function () {
		var eventBus = instanciateEventBud();

		// test simple event
		eventBus.bind('event1', function () {
			ok(true, 'The event bus well catched the event mad_test_event_event1');
			eventBus.element.remove();
			start();
		});
		eventBus.trigger('event1');
	});

});