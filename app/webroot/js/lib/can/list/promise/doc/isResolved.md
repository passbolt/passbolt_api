@function can.List.prototype.isResolved
@parent can.List.plugins.promise

@signature `list.isResolved()`

Returns if the [can.List::state state] of the list is resolved.

@return {Boolean} `true` if the list is resolved. `false` if otherwise.

@body

## Use

    var data = new can.Deferred();
    var list = new can.List(data);
    
    list.done(function(){
    	list.isResolved() //-> true
    })
    
    data.resolve(["a","b","c"]);