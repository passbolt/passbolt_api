@function can.List.prototype.done
@parent can.List.plugins.promise

@signature `list.done( doneCallback )`

Add handlers to be called when the list is resolved. This works very similar 
to [jQuery's done](http://api.jquery.com/deferred.done/).

@param {function(can.List)} doneCallback(list)

A function that is called when the list's promise is resolved. 
It will be called with the list instance.

@return {Promise} The list's promise.

@body


## Use

    var data = new can.Deferred();
    var list = new can.List(data);
    
    list.done(function(l){
      l === list //-> true
    });
    
    data.resolved(["a","b"]);
    
