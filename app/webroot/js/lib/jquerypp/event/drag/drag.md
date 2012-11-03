@page jQuery.event.drag
@parent jquerypp

`jQuery.event.drag` provides drag events for jQuery.
A [jQuery.Drag] instance is created on a drag and passed
as a parameter to the drag event handlers.  By calling
methods on the drag event, you can alter the drag's
behavior.

## Drag Events

The drag plugin allows you to listen to the following events:

* `dragdown` - the mouse cursor is pressed down
* `draginit` - the drag motion is started
* `dragmove` - the drag is moved
* `dragend` - the drag has ended
* `dragover` - the drag is over a drop point
* `dragout` - the drag moved out of a drop point

Just by binding on one of these events, you make
the element dragable.  You can change the behavior of the drag
by calling methods on the drag object passed to the event handler.

### Example

Here's a quick example:

    //makes the drag vertical
    $(".drags").on("draginit", function(event, drag){
      drag.vertical();
    })
    //gets the position of the drag and uses that to set the width
    //of an element
    $(".resize").on("dragmove",function(event, drag){
      $(this).width(drag.location.x() - $(this).offset().left   )
    })

## Drag Object

The drag object is passed after the event to drag  event callback functions.
By calling methods and changing the properties of the drag object, you can alter how the drag behaves.

The drag properties and methods:

* `[jQuery.Drag.prototype.cancel cancel]` - stops the drag motion from happening*
* `[jQuery.Drag.prototype.ghost ghost]` - copys the draggable and drags the cloned element*
* `[jQuery.Drag.prototype.horizontal horizontal]` - limits the scroll to horizontal movement*
* `[jQuery.Drag.prototype.location location]` - where the drag should be on the screen*
* `[jQuery.Drag.prototype.mouseElementPosition mouseElementPosition]` - where the mouse should be on the drag*
* `[jQuery.Drag.prototype.only only]` - only have drags, no drops*
* `[jQuery.Drag.prototype.representative representative]` - move another element in place of this element*
* `[jQuery.Drag.prototype.revert revert]` - animate the drag back to its position*
* `[jQuery.Drag.prototype.vertical vertical]` - limit the drag to vertical movement*
* `[jQuery.Drag.prototype.limit limit]` - limit the drag within an element (*limit plugin)*
* `[jQuery.Drag.prototype.scrolls scrolls]` - scroll scrollable areas when dragging near their boundries (*scroll plugin)*

## Demo

Now lets see some examples:

@demo jquery/event/drag/drag.html 1000