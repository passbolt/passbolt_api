@function can.Control.prototype.destroy destroy
@parent can.Control.prototype
@description Remove a Control from an element and clean up the Control.
@signature `control.destroy()`

Prepares a control for garbage collection and is a place to
reset any changes the control has made.

@body
## Allowing Garbage Collection

Destroy is called whenever a control's element is removed from the page using
the library's standard HTML modifier methods. This means that you
don't have to call destroy yourself and it
will be called automatically when appropriate.

The following `Clicker` widget listens on the window for clicks and updates
its element's innerHTML. If we remove the element, the window's event handler
is removed auto-magically:


	Clicker = can.Control({
	"{window} click": function() {
		this.element.html( this.count ?
		this.count++ : this.count = 0 );
	}
	});

	// create a clicker on an element
	new Clicker( "#clickme" );

	// remove the element
	$( '#clickme' ).remove();

The methods you can use that will destroy controls automatically by library:

__jQuery and Zepto__

- `$.fn.remove`
- `$.fn.html`
- `$.fn.replaceWith`
- `$.fn.empty`

__Dojo__

- `dojo.destroy`
- `dojo.empty`
- `dojo.place (with the replace option)`

__Mootools__

- `Element.prototype.destroy`

__YUI__

- `Y.Node.prototype.remove`
- `Y.Node.prototype.destroy`


## Teardown in Destroy

Sometimes, you want to reset a controlled element back to its
original state when the control is destroyed. Overwriting destroy
lets you write teardown code of this manner.

__NOTE__: When overwriting destroy, make sure you call Control's base functionality.

The following example changes an element's text when the control is
created and sets it back when the control is removed:

	Changer = can.Control.extend({
		init: function() {
			this.oldText = this.element.text();
			this.element.text( "Changed!!!" );
		},
		destroy: function() {
			this.element.text( this.oldText );
			can.Control.prototype.destroy.call( this );
		}
	});

	// create a changer which changes #myel's text
	var changer = new Changer( '#myel' );

	// destroy changer which will reset it
	changer.destroy();

## Base Functionality

Control prepares the control for garbage collection by:

- unbinding all event handlers
- clearing references to this.element and this.options
- clearing the element's reference to the control
- removing it's `can.Control.pluginName` from the element's className
