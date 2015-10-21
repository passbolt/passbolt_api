@page can.event can.event
@parent canjs
@test can/event/test.html
@link ../docco/event.html docco
@group can.event.plugins plugins
@group can.event.static static
@release 2.1

@description

Add event functionality into your objects.

The `can.event` object provides a number of methods for handling events in objects. This functionality is best used by mixing the `can.event` object into an object or prototype. However, event listeners can still be used even on objects that don't include `can.event`.

All methods provided by `can.event` assume that they are mixed into an object -- `this` should be the object dispatching the events.

@signature `can.extend(YourClass.prototype, can.event)`

Adds event functionality to `YourClass` objects. This can also be applied to normal objects: `can.extend(someObject, can.event)`.

@body

## Using as a mixin

The easiest way to add events to your classes and objects is by mixing `can.event` into your object or prototype.

```
var SomeClass = can.Construct("SomeClass", {
	init: function() {
		this.value = 0;
	},
	increment: function() {
		this.value++;
		this.dispatch("change", [this.value]);
	}
});
can.extend(SomeClass.prototype, can.event);
```

Now that `can.event` is included in the prototype, we can add/remove/dispatch events on the object instances.

```
var instance = new SomeClass();
instance.on("change", function(ev, value) {
	alert("The instance changed to " + value);
});

// This will dispatch the "change" event and show the alert
instance.increment();
```

## Using without mixing in

The same event functionality from `can.event` can be used, even if the given object doesn't include `can.event`. Every method within `can.event` supports being called with an alternate scope.

```
var obj = {};

can.event.on.call(obj, "change", function() {
	alert("object change!");
});

// This will dispatch the "change" event and show the alert
can.event.dispatch.call(obj, "change");
```
