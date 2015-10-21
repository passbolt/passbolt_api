@function can.List.prototype.then
@parent can.List.plugins.promise

@signature `list.then( doneFilter, [failFilter] )`

Add handlers to be called when the list 
is resolved or rejected. This works very similar 
to [jQuery's done](http://api.jquery.com/deferred.then/).

@param {function(can.List)} doneFilter(list)

A function that is called when the list's promise is resolved. 
It will be called with the list instance. If
the function returns a value it will be used to resolve
the promise returned by `.then`.

@param {function(*)} [failFilter(reason)]

A function that is called when the list's promise is rejected. 
It will be called with the reason. If
the function returns a value it will be used to reject
the promise returned by `.then`.

@return {Promise} A new promise that will be resolved and rejected
based upon what `doneFilter` and `fileFilter` return.

@body


## Use

    var data = new can.Deferred();
    var list = new can.List(data);
    
    var sumDef = list.then(function(l){
      var sum = 0;
      l.each(function(num){ sum += num; });
      return sum;
    });
    
    data.resolved([1,2, 3]);
    
    sumDef.then(function(value){
      value //-> 6
    });
