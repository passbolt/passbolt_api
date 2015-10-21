@function can.Map.prototype.define.get get
@parent can.Map.prototype.define

Specify what happens when a certain property is read on a map. `get` functions
work like a [can.compute] and automatically update themselves when a dependent
observable value is changed.


@signature `get( [lastSetValue] )`

  Defines the behavior when a value is read on a [can.Map]. Used to provide properties that derive their value from 
  other properties of the map, or __update__ their value from 
  the changes in the value that was set. 

  @param {*} [lastSetValue] The value last set by `.attr(property, value)`.  Typically, _lastSetValue_ 
  should be an observable value, like a [can.compute] or promise. If it's not, it's likely 
  that a [can.Map.prototype.define.set define.set] should be used instead.

  @return {*} The value of the property.

@signature `get( lastSetValue, setAttrValue(value) )`

  Asynchronously defines the behavior when a value is read on a [can.Map]. Used to provide property values that
  are available asynchronously. 

  @param {*} lastSetValue The value last set by `.attr(property, value)`.
  
  @param {function(*)} setAttrValue(value) Updates the value of the property. This can be called
  multiple times if needed.
   
@body

## Use

Getter methods are useful for:

 - Defining virtual properties on a map.
 - Defining property values that change with their _internal_ set value. 
 
## Virtual properties


Virtual properties are properties that don't actually store any value, but derive their value 
from some other properties on the map.

Whenever a getter is provided, it is wrapped in a [can.compute], which ensures 
that whenever its dependent properties change, a change event will fire for this property also.

```
var Person = can.Model.extend({
	define: {
		fullName: {
			get: function () {
				return this.attr("first") + " " + this.attr("last");
			}
		}
	}
});

var p = new Person({first: "Justin", last: "Meyer"});

p.attr("fullName"); // "Justin Meyer"

p.bind("fullName", function(ev, newVal){
  newVal //-> "Lincoln Meyer";
});

p.attr("first","Lincoln");
```

## Asyncronous virtual properties

Often, a virtual property's value only becomes available after some period of time.  For example,
given a `personId`, one might want to retrieve a related person:

```
var AppState = can.Map.extend({
  define: {
    person: {
      get: function(lastSetValue, setAttrValue){
        Person.findOne({id: this.attr("personId")})
        	.then(function(person){
        		setAttrValue(person);
        	});
      }
    }
  }
});
```

Asyncronous properties should be bound to before reading their value.  If 
they are not bound to, the `get` function will be called each time.

The following example will make multiple `Person.findOne` requests: 

```
var state = new AppState({personId: 5});
state.attr("person") //-> undefined

// called sometime later ...
state.attr("person") //-> undefined
```

However, by binding, the compute only reruns the `get` function once `personId` changes:

```
var state = new AppState({personId: 5});

state.bind("person", function(){})

state.attr("person") //-> undefined

// called sometime later
state.attr("person") //-> Person<{id: 5}>
```

A template like [can.stache] will automatically bind for you, so you can pass
`state` to the template like the following without binding:

```
var template = can.stache("<span>{{person.fullName}}</span>");
var state = new AppState({});
var frag = template(state);

state.attr("personId",5);
frag.childNodes[0].innerHTML //=> ""

// sometime later
frag.childNodes[0].innerHTML //=> "Lincoln Meyer"

```

The magic tags are updated as `personId`, `person`, and `fullName` change.


## Properties values that change with their _internal_ set value 

A getter can be used to derive a value from a set value. A getter's
`lastSetValue` argument is the last value set by [can.Map::attr]. 

For example, a property might be set to a compute, but when read, provides the value
of the compute.

```
var MyMap = can.Map.extend({
  define: {
    value: {
      get: function( lastSetValue ){
        return lastSetValue();
      }
    }
  }
});

var map = new MyMap();
var compute = can.compute(1);
map.attr("value", compute);

map.attr("value") //-> 1
compute(2);
map.attr("value") //-> 2
```

This technique should only be used when the `lastSetValue` is some form of
observable, that when it changes, can update the `getter` value.

For simple conversions, [can.Map.prototype.define.set] or [can.Map.prototype.define.type] should be used.

## Updating the virtual property value

It's very common (and better performing) to update the virtual property value 
instead of replacing it. 

The following example creates an empty `locationIds` [can.List] when a new
instance of `Store` is created.  However, as `locations` change,
the [can.List] will be updated with the `id`s of the `locations`.


```
var Store = can.Map.extend({
	define: {
		locationIds: {
			Value: can.List 
			get: function(initialValue){
				var ids = [];
				this.attr('locations').each(function(location){
					ids.push(location.attr("id"));
				});
				return initialValue.replace(ids);
			}
		}
	}
});
```