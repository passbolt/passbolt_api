steal('can/util', function(can) {
	
	var id = 0;
	can.addHandler = function(el, ev, handler){
		var node = can.$(el),
			events = can.data(node,"events");
		if(!events){
			can.data(node,"events", events = {})
		}
		if(!events[ev]){
			events[ev] = {};
		}
		if(handler.__bindingsIds === undefined) {
			handler.__bindingsIds=id++;
		} 
		return events[ev][handler.__bindingsIds] = {
			el: el,
			ev: ev,
			handler: handler
		};
	}
	can.removeHandler = function(el, ev, handler){
		var node = can.$(el),
			events = can.data(node,"events"),
			handlers = events[ev],
			handler = handlers[handler.__bindingsIds];
		
		delete handler[cb.__bindingsIds];
				
		if(can.isEmptyObject(handlers)){
			delete handlers[ev]
		}
		if(can.isEmptyObject(events)){
			// clear data
		}
		
		delete handlers[cb.__bindingsIds];
		return handler;
	}
	can.triggerHandler = function(el, ev, args){
		var node = can.$(el),
			events = can.data(node,"events"),
			handlers = events[ev];
		// copy during trigger
		var handlers = can.extend({}, handlers);
		can.each(handlers, function(handleData, bindingId ){
			handleData.apply(el,[ev].concat(args))
		})
	}

	return can;
})
