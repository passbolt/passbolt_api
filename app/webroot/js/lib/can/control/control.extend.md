@function can.Control.extend
@parent can.Control.static

@signature `can.Control.extend( [staticProperties,] instanceProperties )`

Create a new, extended, control constructor 
function. 

@param {Object} [staticProperties] An object of properties and methods that are added the control constructor 
function directly. The most common property to add is [can.Control.defaults].

@param {Object} instanceProperties An object of properties and methods that belong to 
instances of the `can.Control` constructor function. These properties are added to the
control's `prototype` object. Properties that
look like event handlers (ex: `"click"` or `"li mouseenter"`) are setup
as event handlers.

@return {function(new:can.Construct,element,options)} A control constructor function that has been
extended with the provided `staticProperties` and `instanceProperties`.

@body

## Examples

    // Control that writes "hello world"
    HelloWorld = can.Control.extend({
      init: function(element){
        element.text("hello world")  
      }
    });
    new HelloWorld("#message");
    
    // Control that shows how many times
    // the element has been clicked on
    ClickCounter = can.Control.extend({
      init: function(){
         this.count = 0;
         this.element.text("click me")
      },
      "click": function(){
         this.count++;
         this.element.text("click count = "+this.count)
      }
    })
    new ClickCounter("#counter");
 
    // Counter that counts a specified event
    // type
    CustomCounter = can.Control.extend({
      defaults: {
        eventType: "click"
      }
    },{
      init: function(){
        this.count = 0;
        this.element.text(this.options.eventType+" me")
      },
      "{eventType}": function(){
         this.count++;
         this.element.text(this.options.eventType+
           " count = "+
           this.count);
      }
    })
    new CustomCounter("#counter");
    new CustomCounter("#buy",{
      eventType: "mouseenter"
    });
    
