@function can.List.prototype.isRejected
@parent can.List.plugins.promise

@signature `list.isRejected()`

Returns if the [can.List::state state] of the list is rejected.

@return {Boolean} `true` if the list is rejected. `false` if otherwise.

@body

## Use

    var data = new can.Deferred();
    var list = new can.List(data);
    
    list.fail(function(){
    	list.isRejected() //-> true
    })
    
    data.reject("epic fail");
