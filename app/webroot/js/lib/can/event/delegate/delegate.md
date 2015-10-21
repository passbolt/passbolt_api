@hide

@page can.event.delegate
@parent can.event.plugins
@plugin can/event/delegate
@test can/event/delegate/test.html

**This plugin is in development and should not be included in the official documentation.**

	//
	// ```
	// var SomeClass = can.Construct("SomeClass");
	// SomeClass.shortName; // = "SomeClass"
	// SomeClass._shortName; // = "some_class"
	// var ParentClass = can.Construct("ParentClass");
	//
	// // Set up event and propagaton support
	// can.extend(SomeClass.prototype, can.event, { propagate: "parent" });
	// can.extend(ParentClass.prototype, can.event);
	//
	// // Create a couple nodes and associate them using the propagate property
	// var parent = new ParentClass();
	// var child = new SomeClass();
	// child.parent = parent;
	// 
	// // Listen for an event
	// parent.delegate("some_class", "action", function(ev) {
	//   // This will fire if (and only if) ev.target matches "some_class"
	// });
	// 
	// // This triggers the delegate listener!
	// child.dispatch("action");
	// ```
