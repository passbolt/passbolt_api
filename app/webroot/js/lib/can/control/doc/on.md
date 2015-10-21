@function can.Control.prototype.on on
@parent can.Control.prototype
@description Bind an event handler to a Control, or rebind all event handlers on a Control.

@signature `control.on([el,] selector, eventName, func)`
@param {HTMLElement|jQuery collection|Object} [el=this.element]
The element to be bound.  If no element is provided, the control's element is used instead.
@param {CSSSelectorString} selector A CSS selector for event delegation.
@param {String} eventName The name of the event to listen for.
@param {Function|String} func A callback function or the String name of a control function.  If a control
function name is given, the control function is called back with the bound element and event as the first
and second parameter.  Otherwise the function is called back like a normal bind.
@return {Number} The id of the binding in this._bindings.

`on(el, selector, eventName, func)` binds an event handler for an event to a selector under the scope of the given element.

@signature `control.on()`

Rebind all of a control's event handlers.

@return {Number} The number of handlers bound to this Control.

@body
`this.on()` is used to rebind
all event handlers when [can.Control::options this.options] has changed.  It
can also be used to bind or delegate from other elements or objects.

## Rebinding

By using templated event handlers, a control can listen to objects outside
`this.element`.  This is extremely common in MVC programming.  For example,
the following control might listen to a task model's `completed` property and
toggle a strike className like:

	TaskStriker = can.Control({
		"{task} completed": function(){
			this.update();
		},
		update: function(){
			if ( this.options.task.completed ) {
				this.element.addClass( 'strike' );
			} else {
				this.element.removeClass( 'strike' );
			}
		}
	});

	var taskstriker = new TaskStriker({
		task: new Task({ completed: 'true' })
	});

To update the `taskstriker`'s task, add a task method that updates
this.options and rebinds the event handlers for the new task like:

	TaskStriker = can.Control({
		"{task} completed": function(){
			this.update();
		},
		update: function() {
			if ( this.options.task.completed ) {
				this.element.addClass( 'strike' );
			} else {
				this.element.removeClass( 'strike' );
			}
		},
		task: function( newTask ) {
				this.options.task = newTask;
				this.on();
				this.update();
		}
	});

	var taskstriker = new TaskStriker({
		task: new Task({ completed: true })
	});

	// Now, add a new task that is not yet completed
	taskstriker.task(new Task({ completed: false }));

## Adding new events

If events need to be bound to outside of the control and templated event handlers
are not sufficient, you can call this.on to bind or delegate programmatically:

	init: function() {
		// calls somethingClicked( el, ev )
		this.on( 'click', 'somethingClicked' );

		// calls function when the window is clicked
		this.on( window, 'click', function( ev ) {
			// do something
		});
	},
		somethingClicked: function( el, ev ) {
		 // ...
	}