@property {Boolean} can.fixture.on on
@parent can.fixture

`can.fixture.on` lets you programatically turn off fixtures. This is mostly used for testing.

    can.fixture.on = false
    Task.findAll({}, function(){
        can.fixture.on = true;
    })
