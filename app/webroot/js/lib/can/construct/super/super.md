@page can.Construct.super
@parent can.Construct
@plugin can/construct/super
@test can/construct/super/qunit.html
@download http://donejs.com/can/dist/can.construct.super.js

The __super__ provides a `this._super` reference in functions that points to the base function.  For example,
the following creates a `Vehicle` constructor and `Car` constructor that
inherits from it.  `Car`'s `init` function calls `Vehicle`'s base `init` function. 

	var Vehicle = can.Construct({
      init: function(wheels){
        this.wheels=wheels;
      }
    });

    var Car = can.Construct({
      init: function(speed){
        this._super(4);
        this.speed = speed;
      }
    })

`this._super` also works from static properties.  The following example creates methods that can be
raised to the first and second power:

    First = can.Construct({
        raise: function(n) { return n;}
    },{})
    
    Second = First({
        raise: function(n) { return this._super(n)*n;}
    },{})
    
    First.raise(2)  // -> 2
    Second.raise(2) // -> 4

If you want to pass all arguments to `_super` use
[apply](https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Function/apply):

	var EvenBetterTodo = BetterTodo({
		init : function(text, status) {
			this._super.apply(this, arguments);
			this.isEvenbetter = true;
		}
	});