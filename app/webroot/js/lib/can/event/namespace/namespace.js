// # can/event/namespace
// 
// This is a `can/event` plugin that implements the namespacing for events.
// Namespacing allows you to add listeners for a given namespace, then remove 
// them later by just using the namespace.
//
// Namespacing is **disabled** for can.Map-based classes, because Maps use
// the same syntax as namespaces for binding to deep attributes.
//
// ```
// object.bind("change.orange", function() {});
// object.bind("click.orange", function() {});
// 
// // This unbinds all events using the "orange" namespace.
// object.unbind(".orange");
// ```

steal('can/util/can.js', 'can/event', function(can) {
	// ## can.event.addEvent
	// 
	// Adds an event listener (with namespacing support included). 
	var addEvent = can.addEvent;
	can.addEvent = can.event.addEvent = can.event.on = can.event.bind = function(event, fn) {
		// Bypass namespaces for Maps
		// Otherwise it conflicts with map attribute binding.
		if (can.Map && this instanceof can.Map) {
			return addEvent.call(this, event, fn);
		}

		// Split the namespaces out
		if (event && event.indexOf('.') > -1) {
			var namespaces = event.split('.');
			// The event name is the first item in the string
			event = namespaces[0];
		}
		
		// Add the listener using the original addEvent
		addEvent.call(this, event, fn);

		// Inject namespaces if applicable
		if (namespaces && namespaces.length > 1) {
			var events = this.__bindEvents[event];
			// Assign the namespaces property, including the array of all namespaces
			events[events.length-1].namespaces = namespaces.slice(1);
		}

		return this;
	};

	// ## can.event.removeEvent
	// 
	// Removes an event listener (with namespacing support included). 
	var removeEvent = can.removeEvent;
	can.removeEvent = can.event.removeEvent = can.event.off = can.event.unbind = function(event, fn, __validate) {
		// Bypass namespaces for Maps
		// Otherwise it conflicts with map attribute binding.
		if (can.Map && this instanceof can.Map) {
			return removeEvent.call(this, event, fn);
		}

		// Split the namespaces out
		if (event && event.indexOf('.') > -1) {
			var namespaces = event.split('.');
			// The event name is the first item in the string.
			// Also, remove this item from the namespace array for future processing.
			event = namespaces.splice(0,1)[0];
		}

		// Handle namespace-only (no event name).
		if (!event && namespaces && namespaces.length > 0) {
			var allEvents = this.__bindEvents || {},
				self = this;

			// Given each event name, attempt to remove it with all namespaces included.
			can.each(allEvents, function(events, event) {
				// Use **this** overridden removeEvent function.
				// Using the original removeEvent would bypass namespace validation.
				can.removeEvent.call(self, event + '.' + namespaces.join('.'), fn, __validate);
			});
			return this;
		}
		// Handle normal events (with namespace validation where applicable)
		else {
			var isFunction = typeof fn === 'function';
			// Attempt to remove the event listener with the original removeEvent.
			// Include a custom validation function for validating namespaces on events.
			return removeEvent.call(this, event, fn, namespaces ? function(ev) {
				if (ev.namespaces && (__validate ? __validate(ev, event, fn) : (isFunction && ev.handler === fn || !isFunction && (ev.cid === fn || !fn)))) {
					// Verify that **all** namespaces specified are matched.
					for (var i = 0; i < namespaces.length; i++) {
						if (can.inArray(namespaces[i], ev.namespaces) === -1) {
							return false;
						}
					}
					return true;
				}
				return false;
			} : __validate);
		}
	};

	return can.event;
});
