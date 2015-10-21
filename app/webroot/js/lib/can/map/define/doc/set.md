@function can.Map.prototype.define.set set
@parent can.Map.prototype.define

Specify what happens when a value is set on a map attribute.

@signature `set( [newVal,] [setValue] )`

A set function defines the behavior of what happens when a value is set on a
[can.Map]. It is typically used to:

 - Add or remove other attributes as side effects
 - Coerce the set value into an appropriate action
 
The behavior of the setter depends on the number of arguments specified. This means that a
setter like:

    define: {
      prop: {
        set: function(){}
      }
    }

behaves differently than:

    define: {
      prop: {
        set: function(newVal){}
      }
    }

@param {*} [newVal] The [can.Map::define.type type function] coerced value the user intends to set on the
can.Map. 

@param {function(*)} [setValue(newValue)] A callback that can set the value of the property 
asyncronously. 

@return {*|undefined} If a non-undefined value is returned, that value is set as 
the attribute value. 


If an `undefined` value is returned, the behavior depends on the number of
arguments the setter declares:

 - If the setter _does not_ specify the `newValue` argument, the attribute value is set
   to whatever was passed to [can.Map::attr attr].
 - If the setter specifies the `newValue` argument only, the attribute value will be removed.
 - If the setter specifies both `newValue` and `setValue`, the value of the property will not be 
   updated until `setValue` is called.


@body 

## Use

An attribute's `set` function can be used to customize the behavior of when an attribute value is set 
via [can.Map::attr].  Lets see some common cases:

#### Side effects

The following makes setting a `page` property update the `offset`:

    define: {
      page: {
        set: function(newVal){
          this.attr('offset', (parseInt(newVal) - 1) * 
                               this.attr('limit'));
        }
      }
    }
    
The following makes changing `makeId` remove the `modelId` property: 

    define: {
      makeId: {
        set: function(newValue){
          // Check if we are changing.
          if(newValue !== this.attr("makeId")) {
            this.removeAttr("modelId");
          }
          // Must return value to set as we have a `newValue` argument.
          return newValue;
        }
      }
    }
    
#### Asynchronous Setter

The following shows an async setter:

    define: {
      prop: {
        set: function( newVal, setVal){
          $.get("/something", {}, setVal );
        }
      }
    }


## Behavior depends on the number of arguments.

When a setter returns `undefined`, its behavior changes depending on the number of arguments.

With 0 arguments, the original set value is set on the attribute.

    MyMap = can.Map.extend({
      define: {
        prop: {set: function(){}}
      }
    })

    var map = new MyMap({prop : "foo"});

    map.attr("prop") //-> "foo"

With 1 argument, `undefined` will remove the property.  


    MyMap = can.Map.extend({
      define: {
        prop: {set: function(newVal){}}
      }
    })

    var map = new MyMap({prop : "foo"});

    can.Map.keys(map) //-> []

With 2 arguments, `undefined` leaves the property in place.  It is expected
that `setValue` will be called:

    MyMap = can.Map.extend({
      define: {
        prop: {set: function(newVal, setValue){}}
      }
    })

    var map = new MyMap({prop : "foo"});

    map.attr("prop") //-> "foo"

## Side effects

A set function provides a useful hook for performing side effect logic as a certain property is being changed.

For example, in the example below, Paginator can.Map includes a `page` property, which derives its value entirely from other properties (limit and offset).  If something tries to set the `page` directly, the set method will set the value of `offset`:


    var Paginate = can.Map.extend({
      define: {
        page: {
          set: function (newVal) {
            this.attr('offset', (parseInt(newVal) - 1) * this.attr('limit'));
          },
          get: function () {
            return Math.floor(this.attr('offset') / this.attr('limit')) + 1;
          }
        }
      }
    });

    var p = new Paginate({limit: 10, offset: 20});

## Merging

By default, if a value returned from a setter is an object, array, can.Map, or can.List, the effect will be to replace the property with the new object completely. 

    Contact = can.Map.extend({
      define: {
        info: {
          set: function(newVal){
            return newVal;
          }
        }
      }
    })

    var alice = new Contact({info: {name: 'Alice Liddell', email: 'alice@liddell.com'}});
    alice.attr(); // {name: 'Alice Liddell', 'email': 'alice@liddell.com'}
    alice.info._cid; // '.map1'

    alice.attr('info', {name: 'Allison Wonderland', phone: '888-888-8888'});
    alice.attr(); // {name: 'Allison Wonderland', 'phone': '888-888-8888'}
    alice.info._cid; // '.map2'

By contrast, if you access a property of a Map using `.attr`, then change it by calling `.attr` on it directly, the new properties will be merged with the existing nested Map, not replaced.

    var contact = new can.Map({
      'info' : {'breath' : 'smells like roses'}
    });
    var newInfo = {'teeth' : 'shiny and clean'};
    contact.attr('info').attr(newInfo); // info is now a merged object

If you would rather have the new Map or List merged into the current value, not replaced, call
`attr` inside the setter:


    Contact = can.Map.extend({
      define: {
        info: {
          set: function(newVal){
            this.info.attr(newVal);
            return this.info;
          }
        }
      }
    })

    var alice = new Contact({info: {name: 'Alice Liddell', email: 'alice@liddell.com'}});
    alice.attr(); // {name: 'Alice Liddell', 'email': 'alice@liddell.com'}
    alice.info._cid; // '.map1'

    alice.attr('info', {name: 'Allison Wonderland', phone: '888-888-8888'});
    alice.attr(); // {name: 'Allison Wonderland', email: 'alice@liddell.com', 'phone': '888-888-8888'}
    alice.info._cid; // '.map1'

## Batched Changes

By default, calls to set methods are wrapped in a call to [can.batch.start] and [can.batch.stop], so if a set method has side effects that set more than one property, all these sets are wrapped in a single batch for better performance.