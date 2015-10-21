@function can.view.tag
@parent can.view.static

Register custom behavior for a given tag.

@signature `can.view.tag( tagName, tagHandler(el, tagData) )`

Registers the `tagHandler` callback when `tagName` is found 
in a template. Check out this video where we talk about different possiblities to use can.view.tag:

<iframe width="662" height="372" src="https://www.youtube.com/watch?v=ahjd5OQcs7c" frameborder="0" allowfullscreen></iframe>

@release 2.1

@param {String} tagName A lower-case, hypenated or colon-seperated html 
tag. Example: `"my-widget"` or `"my:widget"`.  It is considered a best-practice to 
have a hypen or colon in all custom-tag names.

@param {function(HTMLElement,can.view.tagData):can.view.Scope} tagHandler(el, tagData) 

Adds custom behavior to `el`.  If `tagHandler` returns data, it is used to 
render `tagData.subtemplate` and the result is inserted as the childNodes of `el`.

@body

## Use

`can.view.tag` is a low-level way to add custom behavior to custom elements. Often, you
want to do this with [can.Component]. However, `can.view.tag` is
useful for when [can.Component] might be considered overkill.  For example, the
following creates a [jQueryUI DatePicker](http://api.jqueryui.com/datepicker/) everytime a
`<jqui-datepicker>` element is found:

    can.view.tag("jqui-datepicker", function(el, tagData){
      $(el).datepicker()
    })


The `tagHandler`'s [can.view.tagData tagData] argument is an object 
that contains the mustache [can.view.Scope scope] and helper [can.view.Options options] 
where `el` is found and a [can.view.renderer subtemplate] that renders the contents of the
template within the custom tag.

## Getting values from the template

`tagData.scope` can be used to read data from the template.  For example, if I wanted
the value of `"format"` within the current template, it could be read like:

    can.view.tag("jqui-datepicker", function(el, tagData){
      $(el).datepicker({format: tagData.scope.attr("format")})
    })

    var template = can.mustache("<jqui-datepicker></jqui-datepicker>")
    template({format: "mm/dd/yy"})
    
`tagData.options` contains the helpers and partials provided 
to the template.  A helper function might need to be called to get the current value of format like:

    can.view.tag("jqui-datepicker", function(el, tagData){
      $(el).datepicker({format: tagData.options.attr("helpers.format")()})
    })

    var template = can.mustache("<jqui-datepicker></jqui-datepicker>")
    template({},{format: function(){
      return "mm/dd/yy"
    }})

## Responding to changing data

Often, data passed to a template is observable.  If you use `can.view.tag`, you must
listen and respond to chagnes yourself.  Consider if format is property on a
`settings` [can.Map] like:

    var settings = new can.Map({
      format: "mm/dd/yy"
    })

You want to update the datepicker if `format` changes.  The easist way to do this
is to use [can.view.Scope Scope's compute] method which returns a get-set
compute that is tied to a key value:


    can.view.tag("jqui-datepicker", function(el, tagData){
    
      var formatCompute = tagData.scope.compute("format"),
          changeHandler = function(ev, newVal){
            $(el).datepicker("option","format", newVal});
          }
      
      formatCompute.bind("change",changeHandler)
      
      changeHandler({}, formatCompute());
      
      ... 
      
    })

    var template = can.mustache("<jqui-datepicker/>")
    template(settings)

If you listen on something outside the tag, it's a good practice to stop listening
when the element is [can.events.removed removed] from the page:

    $(el).bind("removed", function(){
      formatCompute.unbind("change",changeHandler)
    })


## Subtemplate

If content is found within a custom tag like:

    var template = can.mustache(
      "<my-form>\
         <input value="{{first}}"/>\
         <input value="{{last}}"/>\
       </my-form>")

A seperate template function is compiled and passed
as `tagData.subtemplate`.  That subtemplate can
be rendered with custom data and options. For example:

    can.view.tag("my-form", function(el, tagData){
       var frag = tagData.subtemplate({
         first: "Justin"
       }, tagData.options)
       
       $(el).html( frag )
    })
    
    template({
      last: "Meyer" 
    })
    

In this case, the sub-template will not get a value for `last`.  To
include the original data in the subtemplate's scope, [can.view.Scope::add add] to
the old scope like:

    can.view.tag("my-form", function(el, tagData){
       var frag = tagData.subtemplate(
         tagData.scope.add({ first: "Justin" }), 
         tagData.options)
       
       $(el).html( frag )
    })
    
    template({
      last: "Meyer" 
    })
 
