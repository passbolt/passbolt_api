@page jQuery.event.key
@parent jquerypp

`jQuery.event.key` adds a [jQuery.Event.prototype.keyName keyName()] method to the
[jQuery event object](http://api.jquery.com/category/events/event-object/)
that returns a string representation of the current key:

	$("input").on('keypress', function(ev){
	  // Don't allow backspace keys
	  if(ev.keyName() == '\b') {
	    ev.preventDefault()
	  }
	  if(ev.keyName() == 'f1') {
	    alert('I could be a tooltip for help')
	  }
	})

The following keynames are mapped by default:

* `\b` - backspace
* `\t` - tab
* `\r` - enter key
* `shift`, `ctrl`, `alt`
* `pause-break`, `caps`, `escape`, `num-lock`, `scroll-loc`, `print`
* `page-up`, `page-down`, `end`, `home`, `left`, `up`, `right`, `down`, `insert`, `delete`
* `' '` - space
* `0-9` - number key pressed
* `a-z` - alpha key pressed
* `num0-9` - number pad key pressed
* `f1-12` - function keys pressed
* Symbols: `/`, `;`, `:`, `=`, `,`, `-`, `.`, `/`, `[`, `\`, `]`, `'`, `"`

## Supporting international keyboards

The keyCode values of `keydown` and `keyup` for international keys differ between browsers. Since it is not possible
to retrieve the current keyboard layout using JavaScript, `$.event.key` needs to be provided with custom key mappings
for keyboard layouts you want to support.

The following tool generates a `$.event.key` mapping from your current keyboard layout and Browser.
Just press the keys that don't get recognized properly and they will be added to the code block
which you can copy and provide in your application:

@iframe jquery/event/key/customizer.html 600

Generally it is recommended to use `keypress` to retrieve the actual character being pressed.

> Note: In Mac OS Firefox (currently version 12 and 13) won't return any values at all for international
characters on *keydown* and *keyup*.

## Demo

@demo jquery/event/key/key.html
