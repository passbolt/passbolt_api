@property {can.NodeList} can.Control.prototype.element element
@parent can.Control.prototype
@description The element passed to the Control when creating a new instance.

@body

The control instance's HTMLElement (or window) wrapped by the
util library for ease of use.

It is set by the first parameter to `new can.Construct( element, options )`
in [can.Control::setup].  By default, a control listens to events on `this.element`.

### Example - NodeList

The following `HelloWorld` control sets the control`s text to "Hello World":

	HelloWorld = can.Control({
		init: function(){
			this.element.text( 'Hello World' );
		}
	});

	// create the controller on the element
	new HelloWorld( document.getElementById( '#helloworld' ) );

## Wrapped NodeList

`this.element` is a wrapped NodeList of one HTMLELement (or window).  This
is for convenience in libraries like jQuery where all methods operate only on a
NodeList.  To get the raw HTMLElement, write:

	this.element[0] //-> HTMLElement

The following details the NodeList used by each library with
an example of updating its text:

__jQuery__ `jQuery( HTMLElement )`

 this.element.text("Hello World")

__Zepto__ `Zepto( HTMLElement )`

 this.element.text("Hello World")

__Dojo__ `new dojo.NodeList( HTMLElement )`

 this.element.text("Hello World")

__Mootools__ `$$( HTMLElement )`

 this.element.empty().appendText("Hello World")

__YUI__

 this.element.set("text", "Hello World")

## Changing `this.element`

Sometimes you don't want what's passed to `new can.Control`
to be `this.element`.  You can change this by overwriting
setup or by unbinding, setting this.element, and rebinding.

### Overwriting Setup

The following Combobox overwrites setup to wrap a
select element with a div.  That div is used
as `this.element`. Notice how `destroy` sets back the
original element.

	Combobox = can.Control({
		setup: function( el, options ) {
			this.oldElement = $( el );
			var newEl = $( '<div/>' );
			this.oldElement.wrap( newEl );
			can.Control.prototype.setup.call( this, newEl, options );
		},
		init: function() {
			this.element //-> the div
		},
		".option click": function() {
			// event handler bound on the div
		},
		destroy: function() {
			var div = this.element; //save reference
			can.Control.prototype.destroy.call( this );
			div.replaceWith( this.oldElement );
		}
	});

### Unbinding, setting, and rebinding.

You could also change this.element by calling
[can.Control::off], setting this.element, and
then calling [can.Control::on] like:

	move: function( newElement ) {
		this.off();
		this.element = $( newElement );
		this.on();
	}