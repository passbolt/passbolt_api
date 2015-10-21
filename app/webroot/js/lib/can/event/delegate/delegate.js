// # can/event/delegate
// 
// This is a `can/event` plugin that implements the ability to delegate events 
// on normal objects. Delegation depends on the inclusion of the 
// `can/event/propagate` for event propagation/bubbling. There is an additional dependency 
// on `can/construct`, as in order to have a friendly delegation syntax, it needs to know 
// a little about how to describe the objects (in this case, the shortName on the constructor).

steal('can/util/can.js', 'can/event', 'can/event/propagate', 'can/construct', function(can) {

	// ## can.event.delegate
	//
	// Adds a delegate event listener.
	/**
	 * @function can.event.delegate.delegate
	 * @parent can.event.delegate
	 * @plugin can/event/delegate
	 * @signature `obj.delegate( selector, event, handler )`
	 *
	 * Adds a delegate event listener.
	 *
	 * @param {String} selector A selector to match against the stack of propagated objects.
	 *                          The names used are based on the names of the constructors of 
	 *													the original objects.
	 * @param {Object|String} event The event name or object to listen to.
	 * @param {Function} handler The handler to call when the event occurs.
	 * @return {Object} this
	 */
	can.event.delegate = function(selector, event, handler) {
		// Split the selector into parts that can be verified
		var parts = selector && selector.split(/\s+/),
			// Implement the custom delegation handler
			// This is used to verify the selector prior to executing the original handler
			delegate = function(ev) {
				// Verify descendants against the selector
				// These descendants are tracked in the `can/event/propagate` plugin
				for (var i = 0, j = 0, descendant; j < parts.length && (descendant = ev.descendants[i]); i++) {
					// A descendant name is considered valid if it matches the `shortName` or `_shortName`
					// properties on the constructor. Generally, this assumes that `can.Construct` or a 
					// similar can-based class is used (which defines those properties by default).
					if (descendant.constructor && (parts[j] === descendant.constructor._shortName || parts[j] === descendant.constructor.shortName)) {
						j++;
					}
				}

				// Only if every part of the selector was matched, execute the handler
				if (j >= parts.length) {
					return handler.apply(this, arguments);
				}
			};

		// Cache the selector and handler, so that we can undelegate later with only that information.
		delegate.handler = handler;
		delegate.selector = selector;
		return can.addEvent.call(this, event, delegate);
	};

	// ## can.event.undelegate
	//
	// Removes a delegate event listener.
	/**
	 * @function can.event.delegate.undelegate
	 * @parent can.event.delegate
	 * @plugin can/event/delegate
	 * @signature `object.undelegate( selector, event, handler )`
	 *
	 * Removes a delegate event listener.
	 *
	 * @param {String} selector A selector to match against the stack of propagated objects.
	 * @param {Object|String} event The event name or object to listen to.
	 * @param {Function} handler The handler to call when the event occurs.
	 * @return {Object} this
	 */
	can.event.undelegate = function(selector, event, fn) {
		var isFunction = typeof fn === 'function';
		// Attempt to remove the event, with a custom verification function
		return can.removeEvent.call(this, event, fn, function(ev) {
			// This allows us to check for the cached selector and handler
			// as part of the verification. This is necessary because the user isn't 
			// going to have access to the custom handler generated for delegation.
			return isFunction && (ev.handler === fn || (ev.handler.handler === fn && ev.handler.selector === selector)) || !isFunction && ev.cid === fn;
		});
	};
});
