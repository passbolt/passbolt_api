@class can.Observe
@parent canjs
@test can/observe/qunit.html

can.Observe provides the observable pattern for
JavaScript Objects. It lets you

  - Set and remove property or property values on objects
  - Listen for changes in objects and arrays
  - Work with nested properties


## Creating an Observe

To create an observable object, use `new can.Observe( [obj] )` like:

    var person = new can.Observe({ name: 'justin', age: 29 });
    
To create an observable array, use `new can.Observe.List( [array] )` like:

    var hobbies = new can.Observe.List([
    					'programming', 
                        'basketball',
                        'nose picking'
    ]);

  
can.Observe and [can.Observe.List] are very similar. In fact,
can.Observe.List inherits can.Observe and only adds a few extra methods for
manipulating arrays, like [can.Observe.List::push push].  See
[can.Observe.List] for more information about lists.

`Observe` works with nested objects and arrays, so the following works:

    var data = { 
      addresses: [
        {
          city: 'Chicago',
          state: 'IL'
        },
        {
          city: 'Boston',
          state: 'MA'
        }
      ],
      name: 'Justin Meyer'
    },
    o = new can.Observe( data );
    
_o_ now represents an observable copy of _data_.  

Observe is inherited by [can.Model].

## Getting and Setting Properties

Use [can.Observe::attr attr] to get and set properties.

For example, you can __read__ the property values of _o_ with
`observe.attr( name )` like:

    // read name
    o.attr( 'name' ) //-> Justin Meyer
    
And __set__ property names of _o_ with 
`observe.attr( name, value )` like:

    // update name
    o.attr( 'name', 'Brian Moschel' ) //-> o

Observe handles nested data.  Nested Objects and
Arrays are converted to can.Observe and 
can.Observe.Lists.  This lets you read nested properties 
and use can.Observe methods on them.  The following 
updates the second address (Boston) to 'New York':

    o.attr( 'addresses.1' ).attr({
      city: 'New York',
      state: 'NY'
    });

`attr()` can be used to get all properties back from the observe:

    o.attr() // -> 
    { 
      addresses: [
        {
          city: 'Chicago',
          state: 'IL'
        },
        {
          city: 'New York',
          state: 'MA'
        }
      ],
      name: 'Brian Moschel'
    }


## Listening to property changes

When a property value is changed, observe fires a `change` event.
Calling `bind( 'change', handler( ev, attr, how, newVal, oldVal ) )` listens
to any attribute change that happens on the observe. The handler will be
invoked with the property name that was changed, how it was changed
('add', 'remove', or 'set'), the new value, and the old value:

	o = new can.Observe({});
    o.bind( 'change', function( ev, attr, how, nevVal, oldVal ) {
		// ev    -> { type: 'change' }
		// attr  -> "name"
		// how   -> "add"
		// newVal-> "Justin"
		// oldVal-> undefined 
    });

    o.attr( 'name', 'Justin' );

A more powerful delegation mechanism is also available through the
[can.Observe.delegate] plugin. Calling
`delegate( attr, event, handler( ev, newVal, oldVal ) )` listens
to a specific event on a specific attribute or pattern:

    // listen for name changes
    o = new can.Observe({});
    o.delegate( 'name', 'set', function( ev, newVal, oldVal ) {
    	// ev     -> { type: 'change' }
    	// newVal -> 'Justin'
    	// oldVal -> undefined
    });

    o.attr( 'name', 'Justin' );

@constructor Creates a new Observe with its data.

@param {Object} [obj] a JavaScript Object that will be converted to an observable
