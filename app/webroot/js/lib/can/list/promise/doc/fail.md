@function can.List.prototype.fail
@parent can.List.plugins.promise

@signature `list.fail( failCallback )`

Add handlers to be called when the list is rejected. This works very similar 
to [jQuery's fail](http://api.jquery.com/deferred.fail/).

@param {function(*)} failCallback(reason)

A function that is called when the list's promise is rejected. 
It will be called with the [can.List::reason reason] provided to `reject`.

@return {Promise} The list's promise.

@body


## Use

    var data = new can.Deferred();
    var list = new can.List(data);
    
    list.fail(function(reason){
      reason //-> "borked"
    });
    
    data.reject("borked");