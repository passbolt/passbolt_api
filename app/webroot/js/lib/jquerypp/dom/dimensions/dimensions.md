@page jQuery.dimensions
@parent jquerypp

@plugin jquery/dom/dimensions

`jQuery.dimensions` adds support for animating and setting inner and outer dimensions.
It overwrites

* `[jQuery.fn.outerHeight jQuery.fn.outerHeight(value, includeMargins)]`
* `[jQuery.fn.outerWidth jQuery.fn.outerWidth(value, includeMargins)]`
* `[jQuery.fn.innerHeight jQuery.fn.innerHeight(value)]`
* `[jQuery.fn.innerWidth jQuery.fn.innerWidth(value)]`

to let you set these properties and extends [animate](http://api.jquery.com/animate/) to animate them.

## Use

When writing reusable plugins, you often want to 
set or animate an element's width and height that include its padding,
border, or margin.  This is especially important in plugins that
allow custom styling.

## Quick Examples

     $('#foo').outerWidth(100).innerHeight(50);
     $('#bar').animate({ outerWidth: 500 });
     $('#bar').animate({
       outerWidth: 500,
       innerHeight: 200
     });

## Demo

@demo jquery/dom/dimensions/dimensions.html