@page jQuery.event.fastfix
@parent jquerypp

`jQuery.event.fastfix` provides a faster `jQuery.event.fix` using ES 5 Getters first introduced in
[this blog post](http://bitovi.com/blog/2012/04/faster-jquery-event-fix.html).

## How jQuery Normalizes Events

When an event is received by jQuery, it __normalizes__ the event properties before it
dispatches the event to registered event handlers.  __Normalizing__ means, that it makes sure
the event handler properties are the same across all browsers. For example,
IE does not support `event.relatedTarget`, instead IE  provides `event.toElement` and `event.fromElement`.
jQuery uses those properties to set a `relatedTarget` property.

Event handlers won't be receiving the original event. Instead they are getting a new
[jQuery.Event](http://api.jquery.com/category/events/event-object/) object with properties copied from
the raw HTML event. jQuery does this because it can't set properties on a raw HTML event.

You can get the raw event with `originalEvent` like:

    $("#clickme").bind("click", function( ev ) {
      ev.originalEvent
    })

jQuery creates and normalizes the `jQuery.Event` from the raw event in `jQuery.event.fix`.

## Using ES5 getters

`jQuery.event.fastfix` uses ECMAScript 5 getters for browsers that [support Object.defineProperty](http://kangax.github.com/es5-compat-table/),
which allows `jQuery.event.fix` to avoid copying every property and normalizing it for every event.
Instead getters can do this on-demand. That is, they can lookup the `originalEvent`'s value and normalize it if needed.
`jQuery.event.fastfix` goes through the list of properties that `jQuery.event.fix` copies and creates getters for each one.

In the getter, it checks if that property is special (needs normalizing) and uses that properties special
function to normalize the value.

## Performance

A [basic JSPerf](http://jsperf.com/jquery-event-fix/6) of `jQuery.event.fastfix` shows a
3 to 4 times performance improvement compared to the original `jQuery.event.fix`. Since event handling
and `jQuery.event.fix` are usually a major part of the runtime of a jQuery application including `jQuery.event.fastfix`
can bring you significant performance improvements in modern browsers.
