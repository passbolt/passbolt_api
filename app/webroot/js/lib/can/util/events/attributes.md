@typedef {{}} can.events.attributes
@parent can.events
@release 2.1

The event object dispatched when an attribute changes on an element.

@option {String} attributeName The name of the attribute that was changed.
@option {String} oldValue The old value of the attribute.
@option {HTMLElement} target The attribute that changed.
@option {String} [type="attributes"] The type is always "attributes" for an attributes event.
@option {Boolean} [bubbles=false] Attributes events do not bubble.

@body

## Use

Listen to an `attributes` event on an element with the base-library's [can.$ NodeList]. For example,
with jQuery:

    $(el).bind("attributes", function(ev){
      ev.type // "attributes"
      ev.attributeName // "title"
      ev.oldValue // ""
      ev.target // el
    })
    $(el).attr("title","Mr. Sprinkles")

Listen to an `attributes` event with [can.Control] like:

    can.Control.extend({
      "attributes": function(el, ev){
      
      }
    })

Listen to an `attributes` event with [can.Component::events can.Component's events] object like:

    can.Component.extend({
      tag: "panel",
			events: {
				"attributes": function(el, ev){
					
				}
			}
    })


With jQuery, in browsers that [support MutationObsever](http://caniuse.com/mutationobserver) you
can set attributes direction with `setAttribute` like:

    el.setAttribute("title","Mr. Sprinkles");

To create an `attributes` event in all browsers, you must use the base-library [can.$ NodeList]'s attribute methods.

For jQuery or Zepto, use `$.fn.attr`

     $(el).attr("title","Mr. Sprinkles");

For Mootools use [Element::set](http://mootools.net/docs/core/Element/Element#Element:set):

     $(el).set("title","Mr. Sprinkles")

For Dojo, use `dojo.setAttr` or `dojo.removeAttr`;

     dojo.setAttr(el,"title","Mr. Sprinkles");
     
For YUI use `can.attr.set` like:

    can.attr.set(el,"title","Mr. Sprinkles");

## Asynchronous dispatching

Unlike all other events in CanJS, "attributes" events are dispatched asynchronously.  That means that
attribute event handlers are not fired until the all current operations are complete.  For example:

    $(div).bind("attributes", function(){
       console.log("attributes handler")
    })
    
    console.log("about to change title")
    $(el).attr("title","Mr. Sprinkles");
    console.log("changed title")
   
Ouputs

    > about to change title
    > changed title
    > attributes handler
   

