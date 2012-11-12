steal('funcunit', function () {

	var testEnv = null;
	module("mad.event", {
		// runs before each test
		setup: function () {
			stop();
			S.open('//' + 'mad/test/testEnv/mad.html', function () {
				// store the env windows in a global var for the following unit tests
				testEnv = S.win;
				start();
			});
		},
		// runs after each test
		teardown: function () {}
	});

	test('EventBus: ', function () {
		stop();

		// Create the div element which will embedd the event bus controller
		testEnv.$('body').append('<div id="mad_test_EventBus" />');
		var eventBus = new mad.event.EventBus(S('#mad_test_EventBus'));

		// instantiate
		ok(eventBus instanceof mad.event.EventBus, 'The event bus is well and instance of the mad.event.EventBus');

		// test simple event
		eventBus.bind('mad_test_event_event1', function () {
			ok(true, 'The event bus well catched the event mad_test_event_event1');
			eventBus.element.remove();
			start();
		});
		eventBus.trigger('mad_test_event_event1');
	});

});