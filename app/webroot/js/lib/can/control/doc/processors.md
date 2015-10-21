@property {Object.<can.Control.processor>} can.Control.processors processors
@parent can.Control.static
@description A collection of hookups for custom events on Controls.
@body

`processors` is an object that allows you to add new events to bind
to on a control, or to change how existent events are bound. Each
key-value pair of `processors` is a specification that pertains to
an event where the key is the name of the event, and the value is
a function that processes calls to bind to the event.

The processor function takes five arguments:

- _el_: The Control's element.
- _event_: The event type.
- _selector_: The selector preceding the event in the binding used on the Control.
- _callback_: The callback function being bound.
- _control_: The Control the event is bound on.

Inside your processor function, you should bind _callback_ to the event, and
return a function for can.Control to call when _callback_ needs to be unbound.
(If _selector_ is defined, you will likely want to use some form of delegation
to bind the event.)

Here is a Control with a custom event processor set and two callbacks bound
to that event:

	can.Control.processors.birthday = function(el, ev, selector, callback, control) {
	if(selector) {
	 myFramework.delegate(ev, el, selector, callback);
	 return function() { myFramework.undelegate(ev, el, selector, callback); };
	} else {
	 myFramework.bind(ev, el, callback);
	 return function() { myFramework.unbind(ev, el, callback); };
	}
	};

	can.Control("EventTarget", { }, {
	'birthday': function(el, ev) {
	 // do something appropriate for the occasion
	},
	'.grandchild birthday': function(el, ev) {
	 // do something appropriate for the occasion
	}
	});

	var target = new EventTarget('#person');

When `target` is initialized, can.Control will call `can.Control.processors.birthday`
twice (because there are two event hookups for the _birthday_ event). The first
time it's called, the arguments will be:

- _el_: A NodeList that wraps the element with id 'person'.
- _ev_: `'birthday'`
- _selector_: `''`
- _callback_: The function assigned to `' birthday'` in the prototype section of `EventTarget`'s
definition.
- _control_: `target` itself.

The second time, the arguments are slightly different:

- _el_: A NodeList that wraps the element with id 'person'.
- _ev_: `'birthday'`
- _selector_: `'.grandchild'`
- _callback_: The function assigned to `'.grandchild birthday'` in the prototype section of `EventTarget`'s
definition.
- _control_: `target` itself.

can.Control already has processors for these events:

- change
- click
- contextmenu
- dblclick
- focusin
- focusout
- keydown
- keyup
- keypress
- mousedown
- mouseenter
- mouseleave
- mousemove
- mouseout
- mouseover
- mouseup
- reset
- resize
- scroll
- select
- submit
