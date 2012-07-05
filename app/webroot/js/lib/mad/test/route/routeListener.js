module("MadSquirrel", {
    // runs before each test
    setup: function(){
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
    location.hash = '#!';
});
