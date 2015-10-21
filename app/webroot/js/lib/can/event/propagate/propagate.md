@page can.event.propagate 
@parent can.event.plugins
@plugin can/event/propagate
@test can/event/propagate/test.html
@download http://donejs.com/can/dist/can.event.propagate.js

@description

This changes [can.event.dispatch can.event.dispatch] to add support for propagating events to parent objects.

This is implemented similarly to how events work in the DOM: Events dispatched on an object will be dispatched on their parent object, all the way up until no more parent objects are defined.

@signature `can.extend(YourClass.prototype, can.event, { propagate: "parent" })`

Adds event functionality with event propagation to `YourClass` objects. This can also be applied to normal objects: `can.extend(someObject, can.event, { propagate: "parent" })`.

The extra object, `{ propagate: "parent" }`, is used to define which object property is that object's parent. With the `propagate` property set to `"parent"`, any event dispatched on the `YourClass` instances will also be dispatched on `instance.parent` if it exists.

## Using propagation

In order to add propagation to an object or prototype, mix it into the object using `can.extend` along with whatever property should be considered the propagation property.

```
var SomeClass = can.Construct("SomeClass", {
	init: function() {
		this.value = 0;
	},
	setParent: function(obj) {
		this.parent = obj;
		this.dispatch("parent", [this, obj]);
	}
});
can.extend(SomeClass.prototype, can.event, { propagate: "parent" });
```

Now that propagation has been added, events will be dispatched up the tree for as long as a valid propagate property exists.

```
var instance = new SomeClass();
var root = new SomeClass();

root.on("parent", function(ev, obj, parent) {
	// obj has set root as its parent
});

// This triggers the "parent" event on instance
// The "parent" event then propagates and triggers on root as well!
instance.setParent(root);
```

## Stopping propagation

When using the propagate plugin, `stopPropagation()` and `isPropagationStopped()` methods will be added to the event object. These methods can be used to prevent the event from propagating further up the tree.

```
SomeClass.prototype.setParent = function(obj) {
	this.parent = obj;
	var event = this.dispatch("parent", [this, obj]);

	console.log(event.isPropagationStopped()); // => true
};

instance.on("parent", function(ev, obj, parent) {
	// Don't let this propagate past this object
	ev.stopPropagation();
});

root.on("parent", function(ev, obj, parent) {
	// This code is never reached
});

// This will execute the instance listener, but not the
// root listener, because propagation has been stopped.
instance.setParent(root);
```

## Preventing default functionality

When using the propagate plugin, `preventDefault()` and `isDefaultPrevented()` methods will be added to the event object. An object might implement some logic that will be executed after an event is dispatched. In the case that this logic should be optional dependent on the event handlers, default prevention can be used.

```
SomeClass.prototype.setParent = function(obj) {
	this.parent = obj;
	var event = this.dispatch("parent", [this, obj]);

	// Only execute this code if the default isn't prevented
	if (!event.isDefaultPrevented()) {
		this.parent.children = this.parent.children || [];
		this.parent.children.push(this);
	}
};

instance.on("parent", function(ev, obj, parent) {
	// Don't let the default functionality execute
	ev.preventDefault();
});
```
