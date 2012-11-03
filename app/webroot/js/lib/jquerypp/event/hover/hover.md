@page jQuery.event.hover
@parent jquerypp

`jQuery.event.hover` provides delegate-able hover events.
A hover happens when the mouse stops moving over an element for a period of time.
You can listen to the following events:

* `[jQuery.event.hover.hoverinit hoverinit]` - called on mouseenter, use this event to customize [jQuery.Hover.prototype.delay] and [jQuery.Hover.prototype.distance]
* `[jQuery.event.hover.hoverenter hoverenter]` - an element is being hovered
* `[jQuery.event.hover.hovermove hovermove]` - the mouse moves on an element that has been hovered
* `[jQuery.event.hover.hoverleave hoverleave]` - the mouse leaves the element that has been hovered

## Quick Example

The following examples listens to `hoverenter` to add a class to style the element,
and removes the class on `hoverleave`.

	$('#menu').on({
	  "hoverenter" : function() {
	    $(this).addClass("hovering");
	  },
	  "hoverleave" : function() {
	    $(this).removeClass("hovering");
	  }
	});

## Configuring Distance and Delay

An element is hovered when the mouse moves less than a certain distance in specific time over the element.

You can configure that distance and time by adjusting the `distance` (in pixels) and `delay` (in ms) values.
It can either be set globally by adjusting the static properties [jQuery.Hover.static.delay] and [jQuery.Hover.static.distance]:
Or you can adjust [jQuery.Hover.prototype.delay] and [jQuery.Hover.prototype.distance] for an individual hover during `hoverinit`:

	$(".option").on("hoverinit", function(ev, hover){
	  //set the distance to 10px
	  hover.distance(10)
	  //set the delay to 200ms
	  hover.delay(10)
	})

## Demo

@demo jquery/event/hover/hover.html