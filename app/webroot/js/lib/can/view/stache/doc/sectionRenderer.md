@typedef {function()} can.stache.sectionRenderer() sectionRenderer
@parent can.stache.types 

@description Renders a section. These functions are usually provided as `.fn` and
`.inverse` on a stache helper's [can.stache.helperOptions options].

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

@return {documentFragment|String} Returns the rendered result of the helper. If the
section is within a tag, like:

    <h1 {{#helper}}class='power'{{/helper}}>

a String is returned.  

If the section is outside a tag like: 

    <div> {{#helper}}<h2>Tasks</h2>{{/helper}} </div>
    
a documentFragment is returned.

@body

## Use

Renderer functions are provided to stache [can.stache.helper helpers] on 
the [can.stache.helperOptions options] argument and are used to render the
content between sections. The `context` and `helpers` option let you control
the data and helpers used to render the section.

The following example adds `{first: "Justin"}` to the lookup 
data. Notice how the section has access to `first` and `last`.

    can.stache.registerHelper("myhelper", function(options){
      var section = options.fn({first: "Justin"});
      return $("<h1>").append( section );
    })

    var template = can.stache(
      "{{#helper}}{{first}} {{last}}{{/helper}}");
      
    template({last: "Meyer"}) //-> <h1>Justin Meyer</h1>

If no `context` is provided, the current context is passed.  Notice
how the section has access to `last`:

    can.stache.registerHelper("myhelper", function(options){
      
       var section = options.fn();
       return $("<h1>").append( section );
      
    });

    var template = can.stache(
      "{{#helper}}{{first}} {{last}}{{/helper}}");
      
    template({last: "Meyer"}) //-> <h1> Meyer</h1>
    
If a [can.view.Scope] is provided, it is used to render the 
section. Notice how `last` is not available in the section:

    can.stache.registerHelper("myhelper", function(options){
      
      var section = options.fn( new can.view.Scope( {first: "Justin"}) );
      return $("<h1>").append( section );
      
    })

    var template = can.stache(
      "{{#helper}}{{first}} {{last}}{{/helper}}");
      
    template({last: "Meyer"}) //-> <h1>Justin </h1>



