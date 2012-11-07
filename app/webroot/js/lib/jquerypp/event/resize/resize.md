@page jQuery.event.resize
@parent jquerypp

`jQuery.event.resize` allows you to listen to `resize` events on arbitrary elements.
Unlike other events that bubble from the target element to the document the `resize` event will propagate from the outside-in.
This means that outside elements will always resize first.
Trigger the `resize` event whenever the dimensions of an element change and inside elements should adjust as well.

By listening to a resize event, you will be alerted whenever a parent element has a `resize` event triggered on it. For example:

    $('#foo').bind('resize', function(){
      // adjust #foo's dimensions
    })

    $(document.body).trigger("resize");

## The 'Right Order'

When a control changes size, typically, you want only internal controls to have to adjust their
dimensions.  Furthermore, you want to adjust controls from the 'outside-in', meaning
that the outermost control adjusts its dimensions before child controls adjust theirs.

`jQuery.event.resize` calls `resize` events in exactly this manner.

When you trigger a `resize` event, it will propagate up the DOM until it reaches
an element with the first resize event
handler.  There it will move the event in the opposite direction, calling the element's
children's resize event handlers.

If your intent is to call `resize` without bubbling and only trigger child element's handlers,
use the following:

    $("#foo").trigger("resize", false);

## Stopping Children Updates

If your element doesn't need to change it's dimensions as a result of the parent element, it should
call `event.stopPropagation()`.  This will stop `resize` from being sent to child elements of the current element.

## Demo

The `resize` event is very helpful for creating application like layouts. The following example uses [jQuery.dimensions] and the `resize` event to implement a common layout. Just drag the blue square to resize the container. Note the yellow top navigation. Even when the text wraps the sizes will still adjust properly. This is not possible using only CSS:

<iframe style="width: 100%; height: 350px" src="http://jsfiddle.net/TcB5y/embedded/result,html,js,css" allowfullscreen="allowfullscreen" frameborder="0">JSFiddle</iframe>
