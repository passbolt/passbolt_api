@function can.stache
@parent canjs
@release 2.1
@group can.stache.static 0 Methods
@group can.stache.pages 1 Pages
@group can.stache.types 2 Types
@group can.stache.tags 3 Basic Tags
@group can.stache.htags 4 Helper Tags
@group can.stache.plugins 5 Plugins


@link ../docco/view/stache/mustache_core.html docco
@test can/view/stache/test/test.html
@plugin can/view/stache
@download http://canjs.us/release/latest/can.stache.js


@description Logic-less Handlebar and Mustache templates with live binding.

@signature `can.stache(template)`

Processes the template and returns a [can.view.renderer] function that renders the template
with data and local helpers.

@param {String} template The text of a mustache template.

@return {can.view.renderer} A renderer function that returns a live document fragment
that can be inserted in the page.

@body

## Use

[Mustache](https://github.com/janl/mustache.js/) and [Handlebar](http://handlebarsjs.com/) 
templates are compatible with can.stache.

Stache templates looks similar to normal HTML except
they contain keys for inserting data into the template
and [can.stache.Sections sections] to enumerate and/or filter the enclosed template blocks.

For example, the following renders a welcome header for
a user and displays the number of messages.

__Stache Template__

	<script id="template" type="text/stache">
		<h1>Welcome {{user}}!</h1>
		<p>You have {{messages}} messages.</p>
	</script>

__JavaScript__

	var data = new can.Map({
		user: 'Tina Fey',
		messages: 0
	});

	var template = can.view("#template", data)
	document.body.appendChild(template);

__HTML Result__

	<h1>Welcome Tina Fey!</h1>
	<p>You have 0 messages.</p>

To update the html using live-binding, change an observable value:

	data.attr('messages', 5)

This updates this paragraph in the HTML Result to:

	<p>You have 5 messages.</p>



can.stache provides significantly more functionality such as:

- [can.stache.Basics Context and Path Basics]
- [can.stache.Sections Sections]
- [can.stache.helpers.partial Partials]
- [can.stache.Acquisition Acquiring Templates]
- [can.stache.Helpers Helpers]
- [can.stache.Binding Live Binding]


## Differences from can.mustache

`can.stache` is largely compatable with [can.mustache].  There are three main differences:

 - Passes values in the scope to [can.Component] with `{key}`.
 - [can.stache.sectionRenderer section renderers] return documentFragments.
 - [can.mustache.helpers.elementCallback Element callbacks] like `{{(el) -> CODE}}` are no longer supported.
 
 
### Passing values in the scope to can.Components

A [can.mustache] template passes values from the scope to a [can.Component]
by specifying the key of the value in the attribute directly.  For example:

    can.Component.extend({
      tag: "my-tag",
      template: "<h1>{{greeting}}</h1>"
    });
    var template = can.mustache("<my-tag greeting='message'></my-tag>");
    
    var frag = template({
      message: "Hi"
    });
    
    frag //-> <my-tag greeting='message'><h1>Hi</h1></my-tag>
   
With stache, you wrap the key with `{}`. For example:

    can.Component.extend({
      tag: "my-tag",
      template: "<h1>{{greeting}}</h1>"
    });
    var template = can.stache("<my-tag greeting='{message}'></my-tag>");
    
    var frag = template({
      message: "Hi"
    });
     
    frag //-> <my-tag greeting='{message}'><h1>Hi</h1></my-tag>

If the key was not wrapped, the template would render:

    frag //-> <my-tag greeting='message'><h1>message</h1></my-tag>
 
Because the attribute value would be passed as the value of `greeting`.
 
### Section renderers return documentFragments

A [can.mustache.sectionRenderer Mustache section renderer] called 
like `options.fn()` or `options.inverse()` would always return a String. For example,
the following would wrap the `.fn` section in an `<h1>` tag:

    can.mustache.registerHelper("wrapH1", function(options.fn()){
       return "<h1>"+options.fn()+"</h1>";
    });
    
    var template = can.mustache("{{#wrapH1}}Hi There!{{/#wrapH1}}");
    template() //-> <h1>Hi There</h1>

`can.stache`'s [can.stache.sectionRenderer section renderers] return documentFragments when sections
are not contained within an html element. This means the result of the previous helper would be:

    <h1>[object DocumentFragment]</h1>

Instead, helper functions should manipulate the document fragment into the desired response.  With
jQuery, this can be done like:

    can.stache.registerHelper("wrapH1", function(options.fn()){
       return $("<h1>").append( options.fn() );
    });
    
    var template = can.stache("{{#wrapH1}}Hi There!{{/#wrapH1}}");
    template() //-> <h1>Hi There</h1>


### Element callbacks are no longer supported

`can.mustache` supported [can.mustache.helpers.elementCallback element callbacks] like `{{(el) -> CODE}}`. These
are not supported in `can.stache`.  Instead, create a helper that returns a function or register 
a [can.view.attr custom attribute].

    can.stache.registerHelper("elementCallback", function(){
      return function(el){
        CODE
      }
    });

    can.view.tag("element-callback", function(el){
      CODE
    })

## Tags

@api can.stache.tags
