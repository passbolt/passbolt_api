module("MadSquirrel", {
    // runs before each test
    setup: function(){
    },
    // runs after each test
    teardown: function(){
    }
});


test('event.EventBus : check the event bus controller', function(){
    stop();
    
    // Create the div element which will embedd the event bus controller
    mad.app.element.after('<div id="mad_test_EventBus" />');
    var eventBus = new mad.event.EventBus('#mad_test_EventBus');
    
    // instantiate
    ok(eventBus instanceof mad.event.EventBus, 'The event bus is well and instance of the mad.event.EventBus');
    
    // test simple event
    eventBus.bind('mad_test_event_event1', function(){
        ok(true, 'The event bus well catched the event mad_test_event_event1');
        eventBus.element.remove();
        start();
    });
    eventBus.trigger('mad_test_event_event1');
});
