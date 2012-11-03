@page can.Control.route 
@parent can.Control
@plugin can/control/route
@test can/control/view/qunit.html
@download http://donejs.com/can/dist/can.control.route.js

The can.Control.route plugin adds a __route__ [can.Control.static.processors processor] to [can.Control].
This allows creating routes and binding to [can.route] in a single step by listening to the _route_ event
and a route part. Route events will be triggered whenever the route changes to the route part
the control is listening to. For example:

	var Router = can.Control({
		init : function(el, options) {
		},

		":type route" : function(data) {
			// the route says anything but todo
		},

		"todo/:id route" : function(data) {
			// the route says todo/[id]
			// data.id is the id or default value
		},

		"route" : function(data){
			// the route is empty
		}
	});

	new Router(window);

`route` without a route part will get called when the route is empty.
The data passed to the event handler is the serialized route data without the
_route_ attribute.

## Demo

The following demo shows the above control in action.
You can edit the hash, follow some example links or directly change the can.route atttributes.
At the top it shows the control action being called and the data passed to it:

@iframe can/control/route/demo.html 700
