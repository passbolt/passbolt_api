@page jQuery.Range
@parent jquerypp

`jQuery.Range` provides text range helpers for creating, moving, and comparing ranges cross browser. You can get the currently selected range by calling [$.Range.current()](jQuery.Range.static.current) a new range can be created by passing

- __undefined or null__ - returns a range with nothing selected
- __HTMLElement__ - returns a range with the node's text selected
- __Point__ - returns a range at the point on the screen.  The point can be specified like:

        //client coordinates
        {clientX: 200, clientY: 300}
        //page coordinates
        {pageX: 200, pageY: 300}
        {top: 200, left: 300}

- __TextRange__ a raw text range object.

to the constructor.

## $.Range

An instance of $.Range offers the following methods:

* `[jQuery.Range::clone clone]` - clones the range and returns a new $.Range object
* `[jQuery.Range::collapse collapse]` - clones the range and returns a new `$.Range` object
* `[jQuery.Range::compare compare]` - compares one range to another range
* `[jQuery.Range::end end]` - sets or returns the end of the range
* `[jQuery.Range::move move]` - move the endpoints of a range relative to another range
* `[jQuery.Range::overlaps overlaps]` - returns if any portion of these two ranges overlap
* `[jQuery.Range::parent parent]` - returns the most common ancestor element of the endpoints in the range
* `[jQuery.Range::rect rect]` - returns the bounding rectangle of this range
* `[jQuery.Range::rects rects]` - returns the client rects
* `[jQuery.Range::start start]` - sets or returns the beginning of the range
* `[jQuery.Range::toString toString]` - returns the text of the range

Note, that the container returned by [jQuery.Range::start start] and [jQuery.Range::end end] can be a text node or cdata section (see [node types](https://developer.mozilla.org/en/nodeType)). It can be checked by comparing the returned elements `nodeType` with `Node.TEXT_NODE` or `Node.CDATA_SECTION_NODE` which you will need to get the element containing the selected text:

    var startNode = range.start().container;
    if( startNode.nodeType === Node.TEXT_NODE ||
      startNode.nodeType === Node.CDATA_SECTION_NODE ) {
      startNode = startNode.parentNode;
    }
    $(startNode).addClass('highlight');

## Examples

Consider an HTML element like

    <div id="text">This is some text</div>`

`$.Range` can be used like this:

    // Get a text range for #text
    var range = $('#text').range();

    // Move the start 5 characters to the right
    range.start('+5');

    // Move the end 5 characters to the left
    range.end('-5');

    // Return the range text
    range.toString() // is some

    // Select the current range
    range.select();

     // get the startOffset of the range and the container
     range.start() //-> { offset: 5, container: HTMLELement }

     //get the most common ancestor element
     var parent = range.parent();
     
     //select the parent
     var range2 = new $.Range(parent);
