@function can.List.prototype.always
@parent can.List.plugins.promise

@signature `list.always( alwaysCallback )`

Add handlers to be called when the list is either resolved or 
rejected. This works very similar 
to [jQuery's always](http://api.jquery.com/deferred.always/).

@param {function(*|can.List)} alwaysCallback(reasonOrList)

A function that is called when the list's promise is resolved 
or rejected. It will be called with the list if the promise is resolved,
or the [can.List::reason reason] if the promise is rejected.

@return {Promise} The list's promise.

@body

## Use

    var data = new can.Deferred();
    var list = new can.List(data);
    
    list.always(function(l){
      l === list //-> true
    });
    
    data.resolved(["a","b"]);
