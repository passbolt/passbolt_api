// Test environnement, the window of the released popup
var testEnv = null;

module("MadSquirrel", {
    // runs before each test
    setup: function(){
//		S.open('../mad.html', function(){
//			// store the env windows in a global var for the following unit tests
//			testEnv = S.win;
//		});
    },
    // runs after each test
    teardown: function(){
    }
});

test('Route.RouteListener : check the application is well listening the hash changes (TODO CHECK WHY IT GETS 2 EVENTS ... and not in the application)', function(){
	stop();
    mad.route.RouteListener.singleton();
    mad.eventBus.bind(mad.APP_NS_ID+'_route_change', function(){
        ok(true, 'The route listener well detect that the route changed '+location.hash);
        start();
    });
    location.hash = '#!extension/controller/action/p1/p2/p3';
});
