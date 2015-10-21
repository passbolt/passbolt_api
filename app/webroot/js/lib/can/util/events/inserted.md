@typedef {{}} can.events.inserted
@parent can.events
@release 2.0

The event object dispatched when an element is inserted into the document.

@option {HTMLElement} target The attribute that changed.
@option {String} [type="inserted"] The type is always "inserted" for an inserted event.
@option {Boolean} [bubbles=false] Inserted events do not bubble.

@body

## Use

Listen to an `inserted` event on an element with the base-library's [can.$ NodeList]. For example,
with jQuery:

    $(el).bind("inserted", function(ev){
      ev.type // "inserted"
      ev.target // el
    })
    
    $(parent).append(el);

Listen to an `inserted` event with [can.Control] like:

    can.Control.extend({
      "inserted": function(el, ev){
      
      }
    })

Call a method when an element is inserted within a template like:

    <div can-inserted="addItem">...</div>

Listen to an `inserted` event with [can.Component::events can.Component's events] object like:

    can.Component.extend({
      tag: "panel",
      events:{
        "inserted": function(el, ev){
        
        }
    })

To create an `inserted` event, you must use the base-library [can.$ NodeList]'s DOM modifier methods.

For jQuery or Zepto, use `$.fn.html`, `$.fn.append`, `$.fn.after`, etc:

     $(parent).html(el);

For Mootools use [Element::grab](http://mootools.net/docs/core/Element/Element#Element:grab):

     $(parent).grab(el)

For Dojo, use [dojo.place](http://dojotoolkit.org/reference-guide/1.7/dojo/place.html);

     dojo.place(el,parent,"last");
     
For YUI, use anything that calls `Y.DOM.addHTML` append:

    Y.one("div.parent").append(el);


