@page jQuery.event.drop
@parent jquerypp

`jQuery.event.drop` provides drop events for [jQuery.event.drag]. By binding to a 
drop event, callback functions will be called during the corresponding phase of the drag motion.
 
## Drop Events

All drop events are called with the native event, an instance of [jQuery.Drop], and [jQuery.Drag]. The following
drop events are supported:

* [jQuery.event.special.dropinit dropinit] - the drag motion is started, drop positions are calculated.
* [jQuery.event.special.dropover dropover] - a drag moves over a drop element, called once as the drop is dragged over the element.
* [jQuery.event.special.dropout dropout] - a drag moves out of the drop element.
* [jQuery.event.special.dropmove dropmove] - a drag is moved over a drop element, called repeatedly as the element is moved.
* [jQuery.event.special.dropon dropon] - a drag is released over a drop element.
* [jQuery.event.special.dropend dropend] - the drag motion has completed.

A drop instance supports the following methods:

* [$.Drop::cache cache] - cache the position of the drop for faster hit detection
* [$.Drop::cancel cancel] - cancel this drop point from responding to drags

If you are adding drop points after the start of a drag motion, call [$.Drop.compile] to
re-calculate all drop points.

## Examples

Here's how to listen for when a drag moves over a drop:

    $('.drop').delegate("dropover", function(ev, drop, drag){
      $(this).addClass("drop-over")
    })

A bit more complex example:

@demo jquery/event/drop/drop.html 1000


## How it works

1. When you bind on a drop event, it adds that element to the list of rootElements.
  RootElements might be drop points, or might have delegated drop points in them.

2. When a drag motion is started, each rootElement is queried for the events listening on it.
  These events might be delegated events so we need to query for the drop elements.

3. With each drop element, we add a Drop object with all the callbacks for that element.
  Each element might have multiple event provided by different rootElements.  We merge
  callbacks into the Drop object if there is an existing Drop object.

4. Once Drop objects have been added to all elements, we go through them and call draginit
  if available.

