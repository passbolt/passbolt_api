@typedef {{isArgument: Boolean}} can.view.Scope.readOptions readOptions
@parent can.view.Scope.types

An options object used to configure [can.view.Scope.read].


@option {function(can.compute|can.Map,Number)} [foundObservable(observe, readIndex)] `foundObservable` is called 
when the first observable is found along the path along the read path.  It's called with
the `readIndex` where the observable was found.

    var data = {person : new can.Map({name: "Justin"})}
    Scope.read( data, 
                ["person.name"],
                {foundObservable: function(observe, readIndex){
                  observe === data.person //-> true
                  readIndex //-> 1
                }} )

@option {function(can.compute|can.Map,Number)} [earlyExit(observe, readIndex)] Is called if a value is not found.

@option {Boolean} [isArgument] If true, this does not try to evaluate the last value if it is a function or 
a compute. 

    MyMap = can.Map.extend({method: function(){}});
    res = Scope.read( new MyMap(), 
                      ["method"],
                      {isArgument: true, proxyMethods: false} );
    res === MyMap.prototype.method //-> true

@option {Array} args An array of arguments to pass to observable prototype methods. 

@option {Boolean} [returnObserveMethods] If true, returns 
methods found on an observable.  Otherwise, it will call the function with `args` as arguments and return the 
value.

    var Dog = can.Map.extend({
      age: function(){
        return this.attr("years")*7
      }
    })

    var dog = new Dog({years: 3});
    
    Scope.read(dog,"age",{}) //-> 21
    
    Scope.read(dog,
               "age",
               {returnObserveMethods: true})() //-> 21 

@option {Boolean} [proxyMethods=true] Set to false to return just the function, preventing returning a function that
always calls the original function with this as the parent. 
