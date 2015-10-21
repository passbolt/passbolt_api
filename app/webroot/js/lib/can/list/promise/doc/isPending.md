@function can.List.prototype.isPending
@parent can.List.plugins.promise

@signature `list.isPending()`

Returns if the [can.List::state state] of the list is pending.

@return {Boolean} `true` if the list is pending. `false` if otherwise.

@body

## Use

    var data = new can.Deferred();
    var list = new can.List(data);
    
    list.isPending() //-> true
    