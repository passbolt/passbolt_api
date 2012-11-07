@page jquerypp jQuery++
@parent index 1
@description jQuery's missing utils and special events

jQuery++ is a collection of useful jQuery libraries that provide
the missing functionality necessary to
implement and organize large-scale jQuery applications.

## DOM Helpers

DOM helpers extend jQuery with extra functionality for
manipulating the DOM. For example, [dimensions] lets you set the 
outer width and height of elements like:

    $('#foo').outerWidth(500);
    
jQuery++ comes with the following DOM helper plugins:

- [jQuery.animate Animate] - Let jQuery use CSS animations.
- [jQuery.cookie Cookie] - Set and get cookie values.
- [jQuery.compare Compare] - Compare the location of two elements rapidly.
- [jQuery.dimensions Dimensions] - Get, set and animate inner and outer dimensions.
- [jQuery.formParams FormParams] - Serializes a form into a JSON-like object.
- [jQuery.Range Range] - Text range utilities.
- [jQuery.styles Styles] - Get multiple css properties quickly.
- [jQuery.selection Selection] - Gets or sets the current text selection.
- [jQuery.within Within] - Returns elements that have a point within their boundaries.

## Special Events

jQuery++ also adds a number of new events to jQuery that make creating responsive applications a lot easier:

- [jQuery.event.default Default] - Provide default behaviors for events.
- [jQuery.event.destroyed Destroyed] - Know when an element is removed from the page.
- [jQuery.event.drag Drag] - Delegatable drag events.
- [jQuery.event.drop Drop] - Delegatable drop events.
- [jQuery.event.fastfix Fastfix] - Speed up jQuery event handling.
- [jQuery.event.hover Hover] - Delegatable hover events.
- [jQuery.event.key Key] - Get the character from a key event.
- [jQuery.event.pause Pause-Resume] - Pause and resume event propagation.
- [jQuery.event.resize Resize] - Listen to resize events on any element.
- [jQuery.event.swipe Swipe] - Delegatable swipe events.

## Utilities

- [jQuery.Vector Vector] - vector math
