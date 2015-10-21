@typedef {{}} can.events.removed
@parent can.events
@release 2.0

The event object dispatched when an element is removed from the document.

@option {HTMLElement} target The attribute that changed.
@option {String} [type="removed"] The type is always "removed" for a removed event.
@option {Boolean} [bubbles=false] Removed events do not bubble.

@body

## Use

Listen to a `removed` event on an element with the base-library's [can.$ NodeList]. For example,
with jQuery:

    $(el).bind("removed", function(ev){
      ev.type // "removed"
      ev.target // el
    })
    
    $(el).remove()


Listen to a `removed` event with [can.Control] like:

    can.Control.extend({
      "removed": function(el, ev){
      
      }
    })

Call a method when an element is removed within a template like:

    <div can-removed="destroyItem">...</div>


Listen to a `removed` event with [can.Component::events can.Component's events] object like:

    can.Component.extend({
      tag: "panel",
      events: {
        "removed": function(el, ev){
        
        }
    })


To create a `removed` event, you must use the base-library [can.$ NodeList]'s DOM modifier methods.

For jQuery or Zepto, use `$.fn.html`, `$.fn.remove`, `$.fn.empty`, etc:

     $(el).remove();

For Mootools use [Element::set](http://mootools.net/docs/core/Element/Element#Element:destroy)

     $(el).destroy()

For Dojo, use `dojo.destroy`;

     dojo.destroy(el);
     
For YUI use `remove` like:

    Y.NodeList(el).remove();



