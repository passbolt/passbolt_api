@page can.view.bindings
@parent canjs
@link ../docco/view/bindings/bindings.html docco

Provides template event bindings and two-way bindings. 

@body

## Use

The `can/view/bindings` plugin provides helpers useful for template declarative
binding and two-way bindings.  This plugin is included by default
in core CanJS.

Template event bindings are documented by [can.view.bindings.can-EVENT can-EVENT]. They let you 
call a function in a [can.mustache Mustache] [can.view.Scope scope] when an event is triggered like:

    var template = can.mustache("<h1 can-click='hello'>Hi</h1>");
    var frag = template({
      hello: function(){
        console.log("Hello There!")
      }
    });
    $(document.body).append(frag);

Two-way bindings are documented by [can.view.bindings.can-value can-value].  This lets you
listen to when element changes its value and automatically update an observable property.

@demo can/view/bindings/doc/hyperloop.html