@function can.view.attr
@parent can.view.static

Register custom behavior for an attribute.

@signature `can.view.attr( attributeName, attrHandler(el, attrData) )`
@release 2.1

Registers the `attrHandler` callback when `attributeName` is found 
in a template.

@param {String|RegExp} attributeName A lower-case attribute name or regular expression
that matches attribute names. Examples: `"my-fill"` or `/my-\w/`.  

@param {function(HTMLElement,can.view.attrData)} attrHandler(el, attrData) 

A function that adds custom behavior to `el`.  

@body

## Use

`can.view.attr` is used to add custom behavior to elements that contain a 
specified html attribute. Typically it is used to mixin behavior (whereas 
[can.view.tag] is used to define behavior).

The following example adds a jQueryUI tooltip to any element that has 
a `tooltip` attribute like `<div tooltip="Click to edit">Name</div>`.


@demo can/view/doc/tooltip.html

## Listening to attribute changes

In the previous example, the content of the tooltip was static. However,
it's likely that the tooltip's value might change. For instance, the template
might want to dynamically update the tooltip like:

    <button tooltip="{{deleteTooltip}}">
      Delete
    </button>

Where `deleteTooltip` changes depending on how many users are selected:

    deleteTooltip: function(){
      var selectedCount = selected.attr("length");
      if(selectedCount) {
        return "Delete "+selectedCount+" users";
      } else {
        return "Select users to delete them.";
      }
    }


The [can.events.attributes attributes] event can be used to listen to when
the toolip attribute changes its value like:


    can.view.attr("tooltip", function( el, attrData ) {
    
      var updateTooltip = function(){
        $(el).tooltip({
          content: el.getAttribute("tooltip"), 
          items: "[tooltip]"
        });
      };
      
      $(el).bind("attributes", function(ev){
        if(ev.attributeName === "tooltip") {
          updateTooltip();
        }
      });
      
      updateTooltip();
			
    })

To see this behavior in the following demo, hover the mouse over the "Delete" button.  Then
select some users and hover over the "Delete" button again:

@demo can/view/doc/dynamic_tooltip.html


## Reading values from the scope.

It's common that attribute mixins need complex, observable data to
perform rich behavior. The attribute mixin is able to read
data from the element's [can.view.Scope scope]. For example, 
__toggle__ and __fade-in-when__ will need the value of `showing` in:

    <button toggle="showing">
      {{#showing}}Show{{else}}Hide{{/showing}} more info</button>
    <div fade-in-when="showing">
      Here is more info!
    </div>
    
These values can be read from [can.view.attrData attrData]'s scope like:

    attrData.scope.attr("showing")

But often, you want to update scope value or listen when the scope value 
changes. For example, the __toggle__ mixin might want to update `showing`
and the __fade-in-when__ mixin needs to know when 
the `showing` changes.  Both of these can be achived by 
using [can.view.Scope::compute compute] to get a get/set compute that is
tied to the value in the scope:

    var showing = attrData.scope.compute("showing")

This value can be written to by `toggle`:


    can.view.attr("toggle", function(el, attrData){
    
      var attrValue = el.getAttribute("toggle")
          toggleCompute = attrData.scope.compute(attrValue);
	
      $(el).click(function(){
        toggleCompute(! toggleCompute() )
      })
	
    })

Or listened to by `fade-in-when`:

    can.view.attr("fade-in-when", function( el, attrData ) {
      var attrValue = el.getAttribute("fade-in-when");
          fadeInCompute = attrData.scope.compute(attrValue),
          handler = function(ev, newVal, oldVal){
            if(newVal && !oldVal) {
              $(el).fadeIn("slow")
            } else if(!newVal){
              $(el).hide()
            }
          }

      fadeInCompute.bind("change",handler);

      ...
    })

When you listen to something other than the attribute's element, remember to
unbind the event handler when the element is [can.events.removed removed] from the page:

    $(el).bind("remove", function(){
      fadeInCompute.unbind(handler);
    });

@demo can/view/doc/fade_in_when.html

## When to call

`can.view.attr` must be called before a template is processed. When [using `can.view` to create a renderer function](http://canjs.com/docs/can.view.html#sig_can_view_idOrUrl_), `can.view.attr` must be called before the template is loaded, not simply before it is rendered.

		//Call can.view.attr first
		can.view.attr('tooltip', tooltipFunction);
		//Preload a template for rendering
		var renderer = can.view('app-template');
		//No calls to can.view.attr after this will be used by `renderer`


