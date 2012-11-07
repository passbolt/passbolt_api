@page can.Construct.proxy
@parent can.Construct
@plugin can/construct/proxy
@test can/construct/proxy/qunit.html
@download http://donejs.com/can/dist/can.construct.proxy.js

`can.Construct.prototype.proxy( funcName, [args...] )` takes a 
function name and returns a new function that
will call the original function with the same `this` 
from which it was created. `proxy` is useful 
for creating callback functions that have 'this' 
set correctly.

The following example increments a `Counter`'s `count`
every second:

	Counter = can.Construct({
	  init : function(){
	    this.count = 0;
	    setTimeout( this.proxy(function(){
	      this.count++;
	    }), 1000 );
	  }
	})
	var counter = new Counter();
	// later check count
	setTimeout(function(){
	  console.log(counter.count)
	},5000)
	
`proxy` also accepts a method name like `this.proxy('methodName')`, allowing 
the previous example to work like:

	Counter = can.Construct({
	  init : function(){
	    this.count = 0;
	    setTimeout( this.proxy('increment'), 1000);
	  },
	  increment : function(){
	    this.count++;
	  }
	})

## Currying Arguments

Pass additional arguments to `proxy` and it will 
fill in arguments on the returning function.  When invoked,
the additional arguments will appear first in the methods
parameters followed by the callback's arguments.

The `Counter` constructor accepts a `by` argument which is used
to increment the count by the `by` amount specified.  

	Counter = can.Construct({
	  init : function( by ) {
	    this.count = 0;
	    setTimeout( this.proxy('increment', by), 1000);
	  },
	  increment : function( by ) {
	    this.count += by;
	  }
	})
    
    // create a counter that increments by 10
    new Counter(10)
	
## 	Piping Functions

`proxy` can take an array of functions to call as 
the first argument.  When the returned callback function
is called each function in the array is passed the return 
value of the prior function.  This is often used
to eliminate currying callback functions.

The `Counter` accepts a callback that will be called with 
the count every second.

	Counter = can.Construct({
	  init : function( by , callback) {
	    this.count = 0;
	    setTimeout( this.proxy(['increment', callback], by), 1000);
	  },
	  increment : function( by ) {
	    this.count += by;
	    // return the arguments passed to the next function
	    return [this.count]
	  }
	})
	
	new Counter(10, function(count){
	  console.log(count);
	})

## `proxy` on Constructors

`proxy` is also available on constructor functions.  Example:

	Counter = can.Construct({
	  start : function(){
	    this.count = 0;
	    setTimeout( this.proxy('increment'), 1000);
	  },
	  increment : function(){
	    this.count++;
	  }
	},{});
	
    Counter.start();
