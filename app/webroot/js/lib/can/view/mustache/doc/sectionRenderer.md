@typedef {function()} can.mustache.sectionRenderer() sectionRenderer
@parent can.mustache.types 

@description Renders a section. These functions are usually provided as `.fn` and
`.inverse` on a mustache helper's [can.mustache.helperOptions options].

@param {*|can.view.Scope} [context] Specifies the data the section is rendered 
with.  If a [can.view.Scope] is provided, that scope is used to render the
section.  If anything else is provided, it is used to create a new scope object
with the current scope as it's parent.  If nothing is provided, the current
scope is used to render the section.

@param {*|can.view.Options} [helpers] Specifies the helpers the section is rendered 
with.  If a [can.view.Options] is provided, that scope is used to render the
section.  If anything else is provided, it is used to create a new scope object
with the current helper scope as it's parent.  If nothing is provided, the current
helper scope is used to render the section.

@return {String} Returns the rendered result of the helper.

@body

## Use

Renderer functions are provided to mustache [can.mustache.helper helpers] on 
the [can.mustache.helperOptions options] argument and are used to render the
content between sections. The `context` and `helpers` option let you control
the data and helpers used to render the section.

The following example adds `{first: "Justin"}` to the lookup 
data. Notice how the section has access to `first` and `last`.

    can.mustache.registerHelper("myhelper", function(options){
      
      return "<h1>"+options.fn({first: "Justin"})+"</h1>"
      
    })

    var template = can.mustache(
      "{{#helper}}{{first}} {{last}}{{/helper}}");
      
    template({last: "Meyer"}) //-> <h1>Justin Meyer</h1>

If no `context` is provided, the current context is passed.  Notice
how the section has access to `last`:

    can.mustache.registerHelper("myhelper", function(options){
      
      return "<h1>"+options.fn()+"</h1>"
      
    })

    var template = can.mustache(
      "{{#helper}}{{first}} {{last}}{{/helper}}");
      
    template({last: "Meyer"}) //-> <h1> Meyer</h1>
    
If a [can.view.Scope] is provided, it is used to render the 
section. Notice how `last` is not available in the section:

    can.mustache.registerHelper("myhelper", function(options){
      
      return "<h1>"+
        options.fn( new can.view.Scope( {first: "Justin"}) )+
        "</h1>"
      
    })

    var template = can.mustache(
      "{{#helper}}{{first}} {{last}}{{/helper}}");
      
    template({last: "Meyer"}) //-> <h1>Justin </h1>



