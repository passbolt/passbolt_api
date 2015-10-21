@function can.Control.prototype.setup setup
@parent can.Control.prototype
@description Perform pre-initialization logic for control instances and classes. 

@signature `control.setup(element, options)`
@param {HTMLElement|NodeList|String} element The element as passed to the constructor.
@param {Object} [options] option values for the control.  These get added to
this.options and merged with [can.Control.static.defaults defaults].
@return {undefined|Array} return an array if you want to change what init is called with. By
default it is called with the element and options passed to the control.

@body

## Lifecycle of `setup`

Setup, when called, does the following:

### Sets this.element

The first parameter passed to new Control( el, options ) is expected to be
an element.  This gets converted to a Wrapped NodeList element and set as
[can.Control.prototype.element this.element].

### Adds the control's name to the element's className

Control adds it's plugin name to the element's className for easier
debugging.  For example, if your Control is named "Foo.Bar", it adds
"foo_bar" to the className.

### Saves the control in $.data

A reference to the control instance is saved in $.data.  You can find
instances of "Foo.Bar" like:

	$( '#el' ).data( 'controls' )[ 'foo_bar' ]

### Merges Options

Merges the default options with optional user-supplied ones.
Additionally, default values are exposed in the static [can.Control.static.defaults defaults]
so that users can change them.

### Binds event handlers

Setup does the event binding described in [can.Control].