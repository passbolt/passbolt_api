steal('funcunit', function(){
	var testEnv = null;
	var eventBus = null;
	module("event", {
		// runs before each test
		setup: function(){
			S.open('./testEnv/mad.html', function(){
				// store the env windows in a global var for the following unit tests
				testEnv = S.win;
				testEnv.mad.controller.AppController.destroy();
				testEnv.mad.controller.AppController.setNs(APP_NS_ID); // Create the div element which will embedd the event bus controller
				testEnv.$('body').append('<div id="mad_test_EventBus" />');
				eventBus = new mad.event.EventBus(testEnv.$('#mad_test_EventBus'));
				testEnv.mad.eventBus = eventBus;
			});
		},
		// runs after each test
		teardown: function(){
			testEnv.mad.controller.AppController.destroy();
		}
	});

	test('Route.RouteListener : check the application is well listening the hash changes (TODO CHECK WHY IT GETS 2 EVENTS ... and not in the application)', function(){
		stop();
		testEnv.mad.route.RouteListener.singleton();
		testEnv.mad.eventBus.bind(testEnv.mad.APP_NS_ID+'_route_change', function(){
			ok(true, 'The route listener well detect that the route changed '+testEnv.location.hash);
			start();
		});
		testEnv.location.hash = '#!extension/controller/action/p1/p2/p3';
	});

});
