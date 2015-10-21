@property {Number} can.fixture.delay delay
@parent can.fixture

`can.fixture.delay` indicates the delay in milliseconds between an ajax request is made and
the success and complete handlers are called.  This only sets
functional synchronous fixtures that return a result. By default, the delay is 200ms.


    steal('can/util/fixtures').then(function(){
        can.fixture.delay = 1000;
    })

