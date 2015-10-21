// # can/event/propagate
// 
// This is a `can/event` plugin that implements the event propagation bubbling.
// The event will be propagated/bubbled on a per object basis for each object 
// that implements the `propagate` property.
//
// ```
// var SomeClass = can.Construct("SomeClass");
// can.extend(SomeClass.prototype, can.event, { propagate: "parentNode" });
// 
// var parent = new SomeClass();
// var child = new SomeClass();
// child.parentNode = parent;
//
// // This will dispatch on both child **and** parent.
// child.dispatch("action");
// ```

steal('can/util/can.js', 'can/event', function(can) {
	// ## can.event.dispatch
	//
	// Adds propagation of events.
	// Typically this will be integrated using `can.extend` on an object.
	// 
	// ```
	// can.extend(Class.prototype, can.event, { propagate: 'prop' })
	// ```
	var dispatch = can.dispatch;
	can.dispatch = can.event.dispatch = can.event.trigger = function (event, args) {
		var propagate = this.propagate || false;

		// Inject propagation into event, when applicable
		if (typeof event.isPropagationStopped === 'undefined') {
			// Initialize the event into an object
			if (typeof event === 'string') {
				event = {
					type: event
				};
			}

			// Add extra event properties
			// This could be done with can.simpleExtend, but this avoids extra logic execution
			var stop = false,
				prevent = false;

			// Add propagation, if applicable
			if (propagate) {
				// Current target should always be the object the event is triggering on
				event.currentTarget = this;
				// Target is always the original object triggering the event
				event.target = event.target || this;
				// Descendants is a cache of the stack of objects generating this event.
				// This is primarily used for the `can/event/delegate` plugin.
				event.descendants = event.target === event.currentTarget ? [] : [event.target];
				// Allow the user to stop propagation
				event.stopPropagation = function() {
					stop = true;
				};
				event.isPropagationStopped = function() {
					return stop;
				};
			}

			// Always add default prevention
			event.preventDefault = function() {
				prevent = true;
			};
			event.isDefaultPrevented = function() {
				return prevent;
			};
		}
		// If the propagated event already exists, just inject the new target
		else if (propagate) {
			// Set the current target when propagating
			event = can.simpleExtend({}, event);
			event.descendants = [event.currentTarget].concat(event.descendants);
			event.currentTarget = this;
		}

		// Call original dispatch
		dispatch.call(this, event, args);

		// Call propagated events
		if (propagate && !event.isPropagationStopped() && this[propagate]) {
			// Call the propagated can.dispatch (otherwise it'll only propagate one level)
			can.dispatch.call(this[propagate], event, args);
		}

		return event;
	};

	return can.event;
});
