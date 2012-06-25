module("MadSquirrel", {
    // runs before each test
    setup: function(){
    },
    // runs after each test
    teardown: function(){
    }
});

test('Uuid : unicity of the stuff with 1,000.000 items', function(){
    
    var uuids = [];
    for(var i=0; i<1000000; i++){
        var id = uuid();
        if(uuids[id]) ok(false);
        uuids[id] = true;
    }
    ok(true);
});